<?php

namespace RTNatePHP\Blogdown\Blog\Support;

class SearchPatterns
{
    static public $PostFormat = '/(\d{4})-([01]?[\d]{1})-([0123]?[\d]{1})-([\S]+).(\w{2,5})\b/';

    static public $ValidPostExtensions = ['md', 'html'];
}