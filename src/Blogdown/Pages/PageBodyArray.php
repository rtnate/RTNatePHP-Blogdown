<?php 

namespace RTNatePHP\Blogdown\Pages;

use ArrayAccess;
use Error;

class PageBodyArray implements ArrayAccess
{
    protected $bodies = [];

    public function __construct(...$bodies)
    {
        $this->push($bodies);
    }

    public function push(...$bodies)
    {
        foreach($bodies as $body)
        {
            if (is_array($body)) $this->pushBodyArray($body);
            else if ($body instanceof PageBody) $this->pushBody($body);
        }
    }

    protected function pushBodyArray(array $bodies)
    {
        foreach($bodies as $body)
        {
            if ($body instanceof PageBody) $this->pushBody($body);
        }
    }

    protected function pushBody(PageBody $body)
    {
        array_push($this->bodies, $body);
    }

    protected function mergeBodyArrays(array $bodies)
    {
        $this->bodies = array_merge($this->bodies, $bodies);
    }

    public function concatentate()
    {
        $result = '';
        foreach($this->bodies as $body)
        {
            $str = $body->html();
            $result .= ('</ br>' . $str);
        }
        return rtrim($str, 6);
    }

    public function concatentateExcerpt()
    {
        $result = '';
        foreach($this->bodies as $body)
        {
            $str = $body->getExcerpt();
            $result .= ('</ br>' . $str);
        }
        return rtrim($str, 6);
    }

    public function concatenateMore()
    {
        $result = '';
        foreach($this->bodies as $body)
        {
            $str = $body->more();
            $result .= ('</ br>' . $str);
        }
        return rtrim($str, 6);
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->bodies);
    }

    public function offsetGet($offset)
    {
        return $this->bodies[$offset];
    }

    public function offsetSet ($offset, $value)
    {
        throw new Error('Unable to set offset on '.static::class);
    }

    public function offsetUnset($offset)
    {
        throw new Error('Unable to unset offset on '.static::class);
    }

    public function toArray()
    {
        return $this->bodies;
    }
}