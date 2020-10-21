<?php

namespace RTNatePHP\Blogdown\Indexing;

use DirectoryIterator;
use Exception as IndexingException;
use SplFileInfo;

class RootDirectoryIndex extends DirectoryIndexItem
{
    protected $childItems = [];
    protected $blogDirectory;
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
        $created->blogDirectory = $config['blog_directory'];
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
        if ($basename == $this->blogDirectory)
        {
            $this->blogDirectory = $fileinfo->getPathname();
        }
        else 
        {
            parent::parseSubdirectory($fileinfo);
        }
    }

    static protected function getDefaultConfiguration()
    {
        return 
        [
            'blog_directory' => '_blog',
            'follow_symlinks' => 'false'
        ];
    }
}
