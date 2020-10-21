<?php

namespace RTNatePHP\Blogdown\Indexing;

use DirectoryIterator;
use SplFileInfo;

class DataSubdirectoryIndexItem extends DirectoryIndexItem 
{
    public function parse()
    {
        $dir = new DirectoryIterator($this->file->getPathname());
        foreach($dir as $fileinfo)
        {
            if (!$fileinfo->isDot())
            {
                $this->parseItem($fileinfo);
            }
        }
    }

    protected function parseItem(SplFileInfo $fileinfo)
    {
        if ($fileinfo->isDir())
        {
            $dir = self::createFromParent($this, $fileinfo->getPathname());
            $this->addChild($dir);
        }
        else if ($fileinfo->isFile())
        {
            $file = DataFileIndexItem::createFromParent($this, $fileinfo->getPathname());
            $this->addChild($file);
        }
    }

    public function route(): string 
    {
        return '';
    }

    public function type(): string 
    {
        return 'data_subdirectory';
    }
}