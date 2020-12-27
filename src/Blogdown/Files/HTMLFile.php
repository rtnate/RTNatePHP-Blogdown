<?php 

namespace RTNatePHP\Blogdown\Files;

class HTMLFile extends TextFile
{
    protected $file_contents = '';
    protected $front_matter = [];

    public function __construct(string $filename)
    {
        parent::__construct($filename, FileType::HTML());
    }
}