<?php 

namespace RTNatePHP\Blogdown\Files;

class InvalidFile extends BaseFile
{

    public function __construct(string $filename)
    {
        parent::__construct($filename, self::FILE_TYPE_INVALID);
    }

    protected function parse(string $filename)
    {
        return;
    }

    public function hasFrontmatter(): bool
    {
        return false;
    }

    public function content(): string
    {
        return '';
    }

    public function data(): array
    {
        return [];
    }

    public function fileContents(): string
    {
        return '';
    }
}