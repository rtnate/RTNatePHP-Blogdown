<?php

namespace RTNatePHP\Blogdown\Pages;

use RTNatePHP\Blogdown\Support\Markdown;
use RTNatePHP\Util\Arr;

class PageBody 
{
    const CONTENT_STRING = 0x0;
    const CONTENT_MARKDOWN = 0x1;
    const CONTENT_HTML = 0x2;
    const CONTENT_UNESCAPED = 0x3;
    
    protected $contentString = '';
    protected $contentType = self::CONTENT_STRING;
    protected $separator = '';
    protected $parseSafe = false;

    /**
     * Constructs a new Page Body object
     * 
     * @param string $content The page body content (HTML, Markdown, or Text)
     * 
     * @param int $content_type The page body content type.
     *          Use one of the following:
     *          - PageBody::CONTENT_STRING - The content is plain text (will be escaped for unsafe html)
     *          - PageBody::CONTENT_MARKDOWN - The content is markdown that should be parsed
     *          - PageBody::CONTENT_HTML - The content is html and should not be escaped for html
     *          - PageBody::CONTENT_UNESCAPED - The content should not be processed at all
     * 
     * @param array $options (optional) An array of additional options:
     * 
     *              [excerpt_separator]: Optional string for deliminating a short excerpt from the body  
     *              [use_safe_mode]: For markdown content, set to false to not escape unsafe html in markdown text.
     * 
     * @return PageBody
     */
    public function __construct(string $content, int $content_type, array $options = [])
    {
        $this->contentString = $content;
        $this->contentType = $content_type;
        $separator = Arr::get($options, 'excerpt_separator', '');
        $this->separator = trim($separator);
        $this->parseSafe = Arr::get($options, 'use_safe_mode', true);
    }

    public function raw()
    {
        return $this->contentString;
    }

    public function type()
    {
        return $this->contentType;
    }

    public function html(string $parentTag = '')
    {
        $parsed = $this->parse();
        if ($parentTag)
        {
            $tag = strtolower($parentTag);
            return "<$tag>$parsed</$tag>";
        }
        else return $parsed;
    }

    public function getExcerpt()
    {
        $split = $this->split();
        if (count($split) > 0) return $split[0];
        else return '';
    }

    public function more()
    {
        $split = $this->split();
        if (count($split) > 1)
        {
            $moreArray = array_slice($split, 1);
            $more = implode("\r\n", $moreArray);
            return $more;
        }
        else return '';
    }

    public function __get($key)
    {
        if ($key == 'content_more' || $key == 'body_more') return $this->more();
        if ($key == 'content' || $key == 'body') return $this->html();
        if ($key == 'excerpt') return $this->getExcerpt();
    }

    protected function parse()
    {
        return self::parseString($this->contentString, $this->contentType, $this->parseSafe);
    }

    protected function split()
    {
        $pattern = "/{$this->separator}/";
        return preg_split($pattern, $this->contentString);
    }

    static protected function parseString(string $str, int $type, bool $safe = true)
    {
        switch($type)
        {
            case self::CONTENT_MARKDOWN:
                return self::parseMarkdown($str, $safe);
            case self::CONTENT_HTML:
            case self::CONTENT_UNESCAPED: 
                return $str;
            default: 
                return self::parseEscaped($str);
        }
    }
    
    static protected function parseMarkdown(string $str, bool $safe = true)
    {
        return Markdown::parse($str, $safe);
    }

    static protected function parseEscaped(string $str)
    {
        return htmlspecialchars($str);
    }
}