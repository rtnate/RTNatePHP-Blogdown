<?php 

namespace RTNatePHP\Blogdown\Indexing;

use DirectoryIterator;
use SplFileInfo;

abstract class DirectoryIndexItem extends BaseIndex
{
    protected $reserved = ['_static', 'README.md', 'LICENSE', 'package.json', '_resources'];

    protected $dataItems = [];

    protected function loadDataDirectory(SplFileInfo $fileinfo)
    {
        if (!$fileinfo->isDir()) return;
        $dir = new DirectoryIterator($fileinfo->getPathname());
        foreach($dir as $file)
        {
            if (!$file->isDot())
                $this->loadDataItem($file);
        }
    }

    protected function loadDataItem(SplFileInfo $fileinfo)
    {
        if ($fileinfo->isDir())
        {
            $dir = DataSubdirectoryIndexItem::createFromParent($this, $fileinfo->getPathname());
            $this->addDataItem($dir);
        }
        else if ($fileinfo->isFile())
        {
            $file = DataFileIndexItem::createFromParent($this, $fileinfo->getPathname());
            $this->addDataItem($file);
        }
    }

    protected function addDataItem(IndexInterface $item)
    {
        $this->dataItems[] = $item;
    }

    protected function fileIsExcluded(string $filename): bool
    {
        if (in_array($filename, $this->reserved)) return true;
        if (substr($filename, 0, 1) == '.') return true;
        return false;
    }

    protected function parseSubdirectory(SplFileInfo $fileinfo)
    {
        $basename = $fileinfo->getBasename();
        //If this is a _data directory, parse as such
        if ($this->fileIsExcluded($basename)) return;
        if ($basename == '_data')
        {
            $this->loadDataDirectory($fileinfo);
        }
        else if (substr($basename, 0, 1) == '_')
        {
            $item = PageDirectoryIndexItem::createFromParent($this, $fileinfo->getPathname());
            $this->addChild($item);
        }
        else 
        {
            $dir = SubdirectoryIndex::createFromParent($this, $fileinfo->getPathname());
            $this->addChild($dir);
        }
    }

    protected function parseFile(SplFileInfo $fileinfo)
    {
        $basename = $fileinfo->getBasename();
        if ($this->fileIsExcluded($basename)) return;
        $item = PageIndexItem::createFromParent($this, $fileinfo->getPathname());
        $this->addChild($item);
    }

    public function getFlattened()
    {
        $results = [$this];
        $children = $this->children();
        foreach($children as $child)
        {
            if ($child instanceof DirectoryIndexItem)
            {
                $results = array_merge($results, $child->getFlattened());
            }
            else array_push($results, $child);
        }
        return $results;
    }
}