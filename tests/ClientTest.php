<?php
namespace Air\FCgi\Test;

use Air\FCgi\Client;
use Air\FCgi\Http\Content\JsonContent;
use Air\FCgi\Http\HttpRequest;
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
        $request = new HttpRequest(1, true);

        $request->withMethod('get');
        $request->withContent(new JsonContent(['a' => 'b']));
        $request->withRequestUri('/poster/share/xxxxx');
        $request->withScriptFilename('/mof/restful-social/public/index.php');


        try {
            var_dump($this->client->execute($request));
            var_dump($this->client->execute($request));
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->assertInstanceOf(\Exception::class, $e);
        }
    }
}
