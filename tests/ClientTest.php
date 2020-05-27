<?php
namespace Air\FCgi\Test;

use Air\FCgi\Client;
use Air\FCgi\Http\Content\UrlEncodedContent;
use Air\FCgi\Http\Stdin\GetStdin;
use Air\FCgi\Request;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @var Client|null
     */
    private $client = null;

    protected function setUp(): void
    {
        $this->client = new Client('192.168.30.77', 9000);
    }

    protected function tearDown(): void
    {
        $this->client = null;
    }

    public function testMessage()
    {
        $stdin = new GetStdin(new UrlEncodedContent(['a' => 'b']));
        $stdin->withRequestUri('poster/share/5ec2086f6da5862738153ffb')
            ->withScriptFilename('/mof/restful-social/public/index.php');

        $request = new Request($stdin);

        try {
            var_dump($this->client->execute($request));
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->assertInstanceOf(\Exception::class, $e);
        }
    }
}
