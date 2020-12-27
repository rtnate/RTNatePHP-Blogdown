<?php 

namespace RTNatePHP\Blogdown\Blog\Indexing;

use DirectoryIterator;
use RTNatePHP\Blogdown\Blog\Support\SearchPatterns;
use RTNatePHP\Blogdown\Indexing\DirectoryIndexItem;
use SplFileInfo;

class PostDirectoryIndex extends DirectoryIndexItem
{
    protected $posts;
    
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
        return '/posts';
    }

    public function type(): string 
    {
        return 'post-directory';
    }

    protected function parseSubdirectory(SplFileInfo $fileinfo)
    {
        $basename = $fileinfo->getBasename();
        //If the subdirectory is a data directory, we can load it
        //Otherwise any subdirectory in _posts is ignored 
        if ($basename == '_data') $this->loadDataDirectory($fileinfo);
        else return;
    }

    protected function parseFile(SplFileInfo $fileinfo)
    {
        $ext = $fileinfo->getExtension();
        //If the file extension is a valid post extension, continue
        if (array_search($ext, SearchPatterns::$ValidPostExtensions))
        {
            $this->loadPost($fileinfo);
        }
    }

    protected function loadPost(SplFileInfo $fileinfo)
    {
        $name = $fileinfo->getFilename();
        $matches = [];
        $validPostName = preg_match(SearchPatterns::$PostFormat, $name, $matches);
        if ($validPostName)
        {

        }
    }

}