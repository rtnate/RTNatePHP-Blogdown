<?php 

namespace RTNatePHP\Blogdown\Indexing;

use SplFileInfo;

abstract class BaseIndex implements IndexInterface 
{

    protected $file;
    protected $parentItem = null;
    protected $childItems = [];

    static public function createFromParent(IndexInterface $parentItem, string $childPath, bool $followSymLinks = true)
    {
        $item = new static($childPath, $followSymLinks);
        $item->attachParent($parentItem);
        return $item;
    }

    public function __construct(string $path, bool $followSymLinks = true)
    {
        $file = new SplFileInfo($path);
        if ($file->isLink() && $followSymLinks == false)
        {
            $real = '';
        }
        else 
        {
            $real = $file->getRealPath();
        }
        if ($real === false) $this->file = new SplFileInfo('');
        else $this->file = new SplFileInfo($real);
        $this->parse();
    }

    abstract public function parse();

    public function exists(): bool 
    {
        if ($this->file->isFile()) return true;
        if ($this->file->isDir()) return true;
        else return false;
    }

    public function filename(): string 
    {
        return $this->file->getFilename();
    }

    public function basename(): string 
    {
        return $this->file->getBasename();
    }

    public function parent(): IndexInterface
    {
        if ($this->parentItem == null) return InvalidFileIndexItem::default();
        else return $this->parentItem;
    }

    public function children(): array
    {
        return $this->childItems;
    }

    public function path(): string
    {
        return $this->file->getPath();
    }

    public function filePath(): string 
    {
        return $this->file->getPathname();
    }

    public function associated(): array
    {
        return [];
    }

    public function info(): SplFileInfo
    {
        return $this->file;
    }

    protected function attachParent(IndexInterface $parentItem)
    {
        $this->parentItem = $parentItem;
    }

    protected function addChild(IndexInterface $item)
    {
        $this->childItems[] = $item;
    }

    protected function routeNameFromFilename(): string
    {
        $filename = $this->file->getBasename('.'.$this->file->getExtension());
        $lower = strtolower($filename);
        $snake = preg_replace('/[\s_]/', '-', $lower);
        return $snake;
    }

    protected function getChildrenArray(): array 
    {
        $children = $this->children();
        $results = [];
        foreach($children as $child)
        {
            $child_array = $child->toArray();
            $results[] = $child;
        }
        return $results;
    }

    public function toArray(): array
    {
        $parent = $this->parent()->toArray();
        $children = array_map(function(IndexInterface $item){
            return $item->filePath();
        }, $this->children());
        $associated = array_map(function(IndexInterface $item){
            return $item->filePath();
        }, $this->associated());
        return 
        [
            'parent' => $this->parent()->filePath(),
            'children' => $children,
            'file' => $this->file->getPathname(),
            'route' => $this->route(),
            'type' => $this->type(),
            'associated' => $associated
        ];
    }    
}