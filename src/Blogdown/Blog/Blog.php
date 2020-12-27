<?php

namespace RTNatePHP\Blogdown\Blog;

use Exception;
use RTNatePHP\Util\Arr;

class Blog 
{
    static protected $defaultConfiguration = [];

    protected $config = [];
    protected $dir = '';

    public function __construct(array $config = [])
    {
        $this->config = array_merge(static::$defaultConfiguration, $config);

        $dir = Arr::get($this->config, 'directory');
        if (!$dir) throw new Exception("Blog Directory must be configured");
    }


    public function buildIndex()
    {

    }
}