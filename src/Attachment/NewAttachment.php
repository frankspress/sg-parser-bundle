<?php


namespace Frankspress\SgParserBundle\Attachment;


class NewAttachment
{

    private $filePath;
    private $fileName;
    private $originalFileName;
    private $type;
    private $size;
    private $error;


    public function getFile() {
        return file_get_contents($this->filePath);
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function setFilePath($filePath): void
    {
        $this->filePath = $filePath;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getOriginalFileName()
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName($originalFileName): void
    {
        $this->originalFileName = $originalFileName;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size): void
    {
        $this->size = $size;
    }

    public function getError(): array
    {
        return $this->error;
    }

    public function setError(string $error): void
    {
        $this->error = $error;
    }

}