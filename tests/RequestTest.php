<?php
namespace Air\FCgi\Test;

use Air\FCgi\FrameParser;
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

        $i = 0;
        while ($i < 1) {
            echo $this->socket->send((string)$request) . PHP_EOL;
            var_dump($this->socket->recv(65535));

            $i++;
        }
    }
}