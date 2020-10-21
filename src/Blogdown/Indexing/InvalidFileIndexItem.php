<?php 

namespace RTNatePHP\Blogdown\Indexing;

use SplFileInfo;

class InvalidFileIndexItem implements IndexInterface 
{
    static protected $instance = null;

    protected function __construct()
    {
        
    }

    static public function default(): IndexInterface
    {
        if (!InvalidFileIndexItem::$instance) 
            InvalidFileIndexItem::$instance = new self;
        return InvalidFileIndexItem::$instance;
    }

    public function parent(): IndexInterface
    {
        return new self;
    }

    public function children(): array
    {
        return [];
    }

    public function path(): string
    {
        return '';
    }

    public function filename(): string
    {
        return '';
    }

    public function filePath(): string
    {
        return '';
    }

    public function basename(): string
    {
        return '';
    }

    public function associated(): array
    {
        return [];
    }

    public function exists(): bool
    {
        return false;
    }

    public function info(): SplFileInfo
    {
        return new SplFileInfo('');
    }

    public function route(): string 
    {
        return '';
    }

    public function type(): string 
    {
        return 'invalid';
    }

    public function toArray(): array
    {
        return 
        [
            'parent' => $this->parent(),
            'children' => [],
            'file' => '',
            'route' => $this->route(),
            'type' => $this->type(),
            'associated' => []
        ];
    }
}