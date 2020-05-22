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
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @var false|resource|null
     */
    private $socket = null;

    protected function setUp(): void
    {
        $this->socket = fsockopen('192.168.30.77', '9000');
    }

    protected function tearDown(): void
    {
        $this->socket = null;
    }

    /**
     * @test
     */
    public function message()
    {
        $stdin = new PostStdin(new MultipartContent(['composer' => './composer.json'], ['a' => 'b']));
        $stdin
            ->setRequestUri('poster/share/5ec2086f6da5862738153ffb')
            ->setScriptFilename('/mof/restful-social/public/index.php');

        $request = new Request($stdin);
        $request->setKeepConn(true);

        //while (true) {
            fwrite($this->socket, (string)$request);

            do {
                $recvData = fread($this->socket, 1024);
                if (!$recvData) {
                    break;
                }

                if (!FrameParser::hasFrame($recvData)) {
                    break;
                }

                do {
                    $records[] = $record = FrameParser::parseFrame($recvData);
                } while (strlen($recvData) !== 0);

                if ($record instanceof EndRequestRecord) {
                    var_dump((new Response($records))->getBody());
                    break;
                }
            } while (true);
        //}
    }
}