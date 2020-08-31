<?php 

namespace RTNatePHP\Blogdown\Pages;

class PageBuilder 
{
    protected $dir = '';

    public function __construct(string $content_root_directory)
    {
        $this->dir = $content_root_directory;
    }
    
}