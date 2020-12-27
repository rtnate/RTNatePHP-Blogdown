<?php

namespace RTNatePHP\Blogdown\Blog;

class BlogIndex 
{
    protected $dir = '';
    
    public function __construct(string $routeDirectory)
    {
        $this->dir = $routeDirectory;
    }
}