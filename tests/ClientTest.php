<?php
namespace Air\FCgi\Test;

use Air\FCgi\Client;
use Air\FCgi\Http\Content\JsonContent;
use Air\FCgi\Http\Content\UrlEncodedContent;
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
        $this->client = new Client('13.124.222.24', 9000);
    }

    protected function tearDown(): void
    {
        $this->client = null;
    }

    public function testMessage()
    {
        $request = new HttpRequest(null, true);

        $request->withMethod('GET');
        $request->withContent(new UrlEncodedContent(['limit' => '20']));
        $request->withRequestUri('/5d19e0f66da58608ac18aa89/line/newest');
        $request->withScriptFilename('/var/web/moftech.net/app/service-social/public/index.php');

        //print_r($request->getParams());

        try {
            var_dump($this->client->execute($request));
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->assertInstanceOf(\Exception::class, $e);
        }
    }
}
