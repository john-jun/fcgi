<?php
declare(strict_types=1);
namespace Air\FCgi;

use Air\FCgi\Http\HttpRequest;
use Air\FCgi\Http\HttpResponse;
use Air\FCgi\Protocol\Constant;
use Air\FCgi\Protocol\Parser;
use Air\FCgi\Protocol\Request;
use Air\FCgi\Protocol\Response;
use Air\SocketClient\NetAddress\TcpNetAddress;
use Air\SocketClient\NetAddress\UnixNetAddress;
use Air\SocketClient\Socket;
use Exception;
use InvalidArgumentException;

/**
 * Class Client
 * @package Air\FCgi
 */
class Client
{
    /**
     * @var Socket
     */
    protected $socket;

    /**
     * Client constructor.
     * @param string $host
     * @param int $port
     * @param bool $ssl
     */
    public function __construct(string $host, int $port = 0, bool $ssl = false)
    {
        if (stripos($host, 'unix:/') === 0) {
            $netAddress = new UnixNetAddress(ltrim(substr($host, strlen('unix:/')), '/'));
        } else {
            $netAddress = new TcpNetAddress($host, $port, $ssl);
        }

        $this->socket = new Socket($netAddress);
    }

    /**
     * @param Request $request
     * @param float|int $timeout
     * @return Response|null
     * @throws Exception
     */
    public function execute(Request $request, float $timeout = -1): ?Response
    {
        try {
            $this->socket->connect($timeout);
            $this->socket->send((string)$request, $timeout);

            $buffer = '';
            do {
                $header = '';
                $length = Constant::HEADER_LEN;

                //read 8 byte header
                do {
                    $data = $this->socket->recv($length);

                    if (is_string($data)) {
                        $header .= $data;
                        $length -= strlen($header);
                    }
                } while ($length);

                $type = ord($header[1]);
                $length = (ord($header[4]) << 8) + ord($header[5]) + ord($header[6]);
                $buffer .= $header;

                //read length content
                while ($length) {
                    $data = $this->socket->recv($length);

                    if (is_string($data)) {
                        $length -= strlen($data);
                        $buffer .= $data;
                    }
                }

                if (Constant::END_REQUEST === $type) {
                    break;
                }
            } while (true);

            do {
                $records[] = Parser::frame($buffer);
            } while (strlen($buffer) !== 0);

            if (!$request->getKeepConn()) {
                $this->socket->close();
            }

            if ($request instanceof HttpRequest) {
                return new HttpResponse($records);
            }

            return new Response($records);
        } catch (Exception $e) {
            $this->socket->close();

            throw $e;
        }
    }

    /**
     * @param string $url
     * @param string $path
     * @param string $data
     * @param float|int $timeout
     * @return string
     * @throws Exception
     */
    public static function call(string $url, string $path, $data = '', float $timeout = -1): string
    {
        $client = new static(...static::parseUrl($url));
        $pathInfo = parse_url($path);

        $path = $pathInfo['path'] ?? '';
        $root = dirname($path);
        $query = $pathInfo['query'] ?? '';
        $documentUri = '/' . basename($path);
        $requestUri = $query ? "{$documentUri}?{$query}" : $documentUri;

        $request = new HttpRequest();
        $request->withContent($data)
            ->withRequestUri($requestUri)
            ->withDocumentUri($documentUri)
            ->withQueryString($query)
            ->withDocumentRoot($root)
            ->withScriptFilename($path);

        $response = $client->execute($request, $timeout);

        return $response->getBody();
    }

    /**
     * @param string $url
     * @return array
     */
    public static function parseUrl(string $url): array
    {
        $url = parse_url($url);
        $host = $url['host'] ?? '';
        $port = $url['port'] ?? 0;

        if (empty($host)) {
            $host = $url['path'] ?? '';

            if (empty($host)) {
                throw new InvalidArgumentException('Invalid url');
            }

            $host = "unix:/{$host}";
        }

        return [$host, $port];
    }
}
