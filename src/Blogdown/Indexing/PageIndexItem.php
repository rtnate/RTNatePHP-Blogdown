<?php 

namespace RTNatePHP\Blogdown\Indexing;

class PageIndexItem extends BaseIndex
{
    public function parse()
    {
    }

    public function children(): array
    {
        return [];
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
        return 'page';
    }
}