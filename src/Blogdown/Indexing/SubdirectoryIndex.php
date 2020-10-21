<?php 

namespace RTNatePHP\Blogdown\Indexing;

use DirectoryIterator;

class SubdirectoryIndex extends DirectoryIndexItem 
{
    public function parse()
    {
        $dir = new DirectoryIterator($this->file->getPathname());
        foreach($dir as $fileinfo)
        {
            if (!$fileinfo->isDot())
            {
                if ($fileinfo->isDir())
                {
                    $this->parseSubdirectory($fileinfo);
                }
                else if ($fileinfo->isFile())
                {
                    $this->parseFile($fileinfo);
                }
            }
        }
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
        return 'subdirectory';
    }
}