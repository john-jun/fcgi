<?php
declare(strict_types=1);

namespace Air\FCgi\Http\Content;

use Air\FCgi\Http\ContentInterface;
use InvalidArgumentException;

/**
 * Class MultipartContent
 * @package Air\FCgi\Http\Content
 */
class MultipartContent implements ContentInterface
{
    /** @var array */
    private $formData;

    /** @var array */
    private $fileData;

    /**
     * @var string
     */
    private $boundaryId;

    /**
     * MultipartContent constructor.
     * @param array $fileData
     * @param array $formData
     */
    public function __construct(array $fileData, array $formData = [])
    {
        $this->fileData = $fileData;
        $this->formData = $formData;
        $this->generateBoundaryId();

        foreach ($fileData as $name => $filePath) {
            $this->addFile((string)$name, (string)$filePath);
        }
    }

    /**
     * @param string $name
     * @param string $filePath
     */
    public function addFile(string $name, string $filePath) : void
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException('File does not exist: ' . $filePath);
        }

        $this->fileData[$name] = $filePath;
    }

    /**
     * @return string
     */
    public function getContentType() : string
    {
        return 'multipart/form-data; boundary=' . $this->boundaryId;
    }

    /**
     * @return string
     */
    public function getContent() : string
    {
        $data = [];

        foreach ($this->formData as $key => $value) {
            $data[] = $this->getFormDataContent($key, $value);
        }

        foreach ($this->fileData as $name => $filePath) {
            $data[] = $this->getFileDataContent($name, $filePath);
        }

        $data[] = '--' . $this->boundaryId . "--\r\n\r\n";

        return implode("\r\n", $data);
    }

    private function getFormDataContent(string $key, string $value) : string
    {
        $data = ['--' . $this->boundaryId];
        $data[] = sprintf("Content-Disposition: form-data; name=\"%s\"\r\n", $key);
        $data[] = $value;

        return implode("\r\n", $data);
    }

    private function getFileDataContent(string $name, string $filePath) : string
    {
        $data = ['--' . $this->boundaryId];
        $data[] = sprintf(
            'Content-Disposition: form-data; name="%s"; filename="%s"',
            $name,
            basename($filePath)
        );

        $data[] = 'Content-Type: application/octet-stream';
        $data[] = "Content-Transfer-Encoding: base64\r\n";
        $data[] = trim(chunk_split(base64_encode((string)file_get_contents($filePath))));

        return implode("\r\n", $data);
    }

    private function generateBoundaryId()
    {
        $this->boundaryId = uniqid();
    }
}
