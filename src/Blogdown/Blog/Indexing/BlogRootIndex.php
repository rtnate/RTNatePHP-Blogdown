<?php

namespace RTNatePHP\Blogdown\Blog\Indexing;

use DirectoryIterator;
use Exception as IndexingException;
use RTNatePHP\Blogdown\Indexing\DirectoryIndexItem;
use SplFileInfo;

class BlogRootIndex extends DirectoryIndexItem
{
    protected $childItems = [];
    protected $dataItems = [];


    static public function create(string $filename, array $configuration = [])
    {
        $file = new SplFileInfo($filename);
        if (!$file->isDir())
        {
            throw new IndexingException('File '.$filename.' is not a directory.');
        }
        $config = array_merge(self::getDefaultConfiguration(), $configuration);
        $created = new self($filename, $config['follow_symlinks']);
        return $created;
    }

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
        return '/';
    }

    public function type(): string 
    {
        return 'root';
    }

    protected function parseSubdirectory(SplFileInfo $fileinfo)
    {
        $basename = $fileinfo->getBasename();

        switch($basename)
        {
            case '_posts':
                $this->loadPostsDirectory($fileinfo);
                break;
            case '_data':
                $this->loadDataDirectory($fileinfo);
                break;
            case '_deleted':
                $this->loadDeletedDirectory($fileinfo);
                break;
            case '_drafts': 
                $this->loadDraftsDirectory($fileinfo);
                break;
            default:
                $this->loadSubdirectory($fileinfo);
                break;
        }
    }

    protected function loadPostsDirectory(SplFileInfo $fileinfo)
    {
        
    }

    protected function loadDeletedDirectory(SplFileInfo $fileinfo)
    {
        
    }

    protected function loadDraftsDirectory(SplFileInfo $fileinfo)
    {
        
    }

    protected function loadSubdirectory(SplFileInfo $fileinfo)
    {
        $basename = $fileinfo->getBasename();
        if (substr($basename, 0, 1) == '_')
        {
            //Ignore directories that start with an underscore
            //$index = new PostSubdirectoryIndex($fileinfo);
        }
    }

    static protected function getDefaultConfiguration()
    {
        return 
        [
            'follow_symlinks' => 'false'
        ];
    }
}
