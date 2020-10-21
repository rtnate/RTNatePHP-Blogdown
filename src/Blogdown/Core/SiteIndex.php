<?php 

namespace RTNatePHP\Blogdown\Core;

use RTNatePHP\Blogdown\Indexing\DirectoryIndexItem;
use RTNatePHP\Blogdown\Indexing\IndexInterface;
use RTNatePHP\Blogdown\Indexing\RootDirectoryIndex;

class SiteIndex 
{
    protected $dir = '';
    protected $entries = [];
    protected $routes = [];
    protected $valid_page_types = ['page', 'page_directory'];

    public function __construct(string $routeDirectory)
    {
        $this->dir = $routeDirectory;
    }

    public function build()
    {
        $index = RootDirectoryIndex::create($this->dir);
        $items = $this->flatten($index);
        foreach($items as $item)
        {
            $this->addEntry($item);
        }
        $this->buildRouteIndex();
        echo var_dump($this->routes);
    }
    
    protected function flatten(IndexInterface $index)
    {
        $items = [];
        $items[] = $index;
        $children = $index->children();
        $flattened = $this->flattenChildren($children);
        $items = array_merge($items, $flattened);
        return $items;
    }

    protected function flattenChildren(array $children)
    {
        $results = [];
        foreach($children as $child)
        {
            if ($child instanceof DirectoryIndexItem)
            {
                $flattened = $child->getFlattened();
                $results = array_merge($results, $flattened);
            }
            else array_push($results, $child);
        }
        return $results;
    }

    protected function addEntry(IndexInterface $entry)
    {
        $path = $entry->filePath();
        $newEntry = $entry->toArray();
        $this->entries[$path] = $newEntry;
    }

    protected function buildRouteIndex()
    {
        foreach($this->entries as $entry)
        {
            $type = $entry['type'];
            if (in_array($type, $this->valid_page_types))
            {
                $this->addRouteItem($entry);
            }
        }
    }

    protected function addRouteItem(array $entry)
    {
        $parent = $entry['parent'];
        $associated = $this->getAssociateParentFiles($parent);
        $route = $entry['route'];
        $item = [
            'path' => $entry['file'],
            'associated' => $associated,
            'type' => $entry['type']
        ];
        $this->routes[$route] = $item;
        if (preg_match('/(.*\/)index$/', $route, $matches))
        {
            $indexRoute = $matches[1];
            $this->routes[$indexRoute] = $item;
        }
    }

    protected function getAssociateParentFiles(string $parent)
    {
        if (!array_key_exists($parent, $this->entries)) return [];
        $associated = [];
        $parent = $this->entries[$parent];
        $parentAssociated = $parent['associated'];
        $associated = array_merge($associated, $parentAssociated);
        $parentsParent = $parent['parent'];
        $parentsParentAssociated = $this->getAssociateParentFiles($parentsParent);
        $associated = array_merge($associated, $parentsParentAssociated);
        return $associated;
    }
}