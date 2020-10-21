<?php 

namespace RTNatePHP\Blogdown\Indexing;

class PageDirectoryIndexItem extends BaseIndex
{
    public function parse()
    {
        
    }

    protected function routeNameFromFilename(): string
    {
        $filename = $this->file->getBasename('.'.$this->file->getExtension());
        $lower = strtolower($filename);
        $lower = ltrim($lower, '_');
        $snake = preg_replace('/[\s_]/', '-', $lower);
        return $snake;
    }

    public function route(): string 
    {
        $parentRoute = $this->parent()->route();
        $parentRoute = rtrim($parentRoute, '/');
        $name = $this->routeNameFromFilename();
        return $parentRoute.'/'.$name;
    }

    public function type(): string 
    {
        return 'page_directory';
    }
}