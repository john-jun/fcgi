<?php
namespace Air\FCgi\Test\Http;

use Air\FCgi\Http\Content\JsonContent;
use Air\FCgi\Http\Content\MultipartContent;
use Air\FCgi\Http\Content\UrlEncodedContent;
use PHPUnit\Framework\TestCase;

class HttpContentTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testJsonContent()
    {
        $data = [];
        while (true) {
            $data[] = uniqid(10);
            if (count($data) === 50) {
                break;
            }
        }

        $json = new JsonContent($data);
        $this->assertStringContainsString('application/json', $json->getContentType());
        $this->assertIsString($json->getContent());
    }

    public function testUrlEncodedContent()
    {
        $data = [];
        while (true) {
            $data[] = uniqid(10);
            if (count($data) === 50) {
                break;
            }
        }

        $urlEncoded = new UrlEncodedContent($data);
        $this->assertStringContainsString('application', $urlEncoded->getContentType());
        $this->assertIsString($urlEncoded->getContent());
    }

    public function testMultipartContent()
    {
        $fileData['gitignore'] = './.gitignore';
        $fileData['composer'] = './composer.json';

        $multipart = new MultipartContent($fileData, ['formData' => 'a']);
        $this->assertStringContainsString('multipart', $multipart->getContentType());
        $this->assertIsString($multipart->getContent());
    }
}