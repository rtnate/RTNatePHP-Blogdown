<?php 

namespace RTNatePHP\Blogdown\Files;

class UnknownFile extends BaseFile
{

    public function __construct(string $filename)
    {
        parent::__construct($filename, FileType::UNKNOWN());
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