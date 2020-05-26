<?php
namespace Air\FCgi\Test;

use Air\FCgi\FastCGI;
use Air\FCgi\Parser;
use Air\FCgi\Http\Content\MultipartContent;
use Air\FCgi\Http\Content\UrlEncodedContent;
use Air\FCgi\Http\Stdin\GetStdin;
use Air\FCgi\Http\Stdin\PostStdin;
use Air\FCgi\Http\Stdin\PutStdin;
use Air\FCgi\Record\EndRequestRecord;
use Air\FCgi\Request;
use Air\FCgi\Response;
use Air\SocketClient\NetAddress\TcpNetAddress;
use Air\SocketClient\Socket;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @var false|resource|null
     */
    private $socket = null;

    protected function setUp(): void
    {
        $this->socket = new Socket(new TcpNetAddress('192.168.30.77', 9000));
    }

    protected function tearDown(): void
    {
        $this->socket = null;
    }

    public function testMessage()
    {
        $stdin = new GetStdin(new UrlEncodedContent(['a' => 'b']));
        $stdin->setRequestUri('poster/share/5ec2086f6da5862738153ffb')
            ->setScriptFilename('/mof/restful-social/public/index.php');

        $request = new Request($stdin);
        $request->setKeepConn(true);

        $this->socket->connect();
        echo PHP_EOL . $this->socket->getConnectUseTime() . PHP_EOL;
        $this->socket->send((string)$request);

        do {
            $buffer = $this->socket->recv(FastCGI::HEADER_LEN);
            if (strlen($buffer) < 8) {
                continue;
            }

            $header = unpack(FastCGI::HEADER_FORMAT, $buffer);
            if (($length = $header['contentLength']) > 0) {
                while ($length && ($data = $this->socket->recv($length)) !== false) {
                    $length -= strlen($data);
                    $buffer .= $data;
                }
            }

            if (($length = $header['paddingLength']) > 0) {
                while ($length && ($data = $this->socket->recv($length)) !== false) {
                    $length -= strlen($data);
                    $buffer .= $data;
                }
            }

            var_dump(Parser::parseFrame($header, $buffer));
            break;
        } while (true);
    }
}