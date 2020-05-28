<?php
namespace Air\FCgi\Test\Http;

use Air\FCgi\Http\Content\JsonContent;
use Air\FCgi\Http\Content\MultipartContent;
use Air\FCgi\Http\Content\UrlEncodedContent;
use Air\FCgi\Http\Message\DeleteMessage;
use Air\FCgi\Http\Message\GetStdin;
use Air\FCgi\Http\Message\HeadStdin;
use Air\FCgi\Http\Message\OptionsStdin;
use Air\FCgi\Http\Message\PatchStdin;
use Air\FCgi\Http\Message\PostStdin;
use Air\FCgi\Http\Message\PutStdin;
use PHPUnit\Framework\TestCase;

class HttpStdinTest extends TestCase
{
    private $json;
    private $multipart;
    private $urlEncoded;

    protected function setUp(): void
    {
        $this->json = new JsonContent(['json' => 'test']);
        $this->multipart = new MultipartContent(['file' => './composer.json'], ['data' => 'test']);
        $this->urlEncoded = new UrlEncodedContent(['url_encode' => 'test']);
    }

    protected function tearDown(): void
    {
        $this->json = null;
        $this->multipart = null;
        $this->urlEncoded = null;
    }

    public function testGet()
    {
        $stdin = new GetStdin($this->json);
        $this->assertIsString($stdin->getContent());

        $stdin = new GetStdin($this->urlEncoded);
        $this->assertIsString($stdin->getContent());
    }

    public function testHead()
    {
        $stdin = new HeadStdin($this->json);
        $this->assertIsString($stdin->getContent());

        $stdin = new HeadStdin($this->urlEncoded);
        $this->assertIsString($stdin->getContent());
    }

    public function testOptions()
    {
        $stdin = new OptionsStdin($this->json);
        $this->assertIsString($stdin->getContent());

        $stdin = new OptionsStdin($this->urlEncoded);
        $this->assertIsString($stdin->getContent());
    }

    public function testPut()
    {
        $stdin = new PutStdin($this->json);
        $this->assertIsString($stdin->getContent());

        $stdin = new PutStdin($this->urlEncoded);
        $this->assertIsString($stdin->getContent());
    }

    public function testPost()
    {
        $stdin = new PostStdin($this->json);
        $this->assertIsString($stdin->getContent());

        $stdin = new PostStdin($this->multipart);
        $this->assertIsString($stdin->getContent());

        $stdin = new PostStdin($this->urlEncoded);
        $this->assertIsString($stdin->getContent());
    }

    public function testPatch()
    {
        $stdin = new PatchStdin($this->json);
        $this->assertIsString($stdin->getContent());

        $stdin = new PatchStdin($this->urlEncoded);
        $this->assertIsString($stdin->getContent());
    }

    public function testDelete()
    {
        $stdin = new DeleteMessage($this->json);
        $this->assertIsString($stdin->getContent());

        $stdin = new DeleteMessage($this->urlEncoded);
        $this->assertIsString($stdin->getContent());
    }
}
