<?php 

namespace RTNatePHP\Blogdown\Pages;

use RTNatePHP\Blogdown\Files\FileInterface;

class PageContentBuilder 
{
    protected $pageTag = '';
    protected $pageFiles = [];

    protected function __construct(string $pageTag, array $pageFiles)
    {
        $this->pageTag = $pageTag;
        $this->pageFiles = $pageFiles;
    }

    protected function buildPageContent()
    {
        $filesSorted = $this->sortFiles();
        $root = $this->createRootPage($filesSorted['root']);
        $data = $this->createDataPages($filesSorted['data']);
        $root->addItems($data);
        return $root;
    }

    protected function sortFiles()
    {
        $rootFiles = [];
        $dataFiles = [];
        foreach($this->pageFiles as $file)
        {
            if ($file instanceof FileInterface)
            {
                $name = $file->filename();
                if ($name == $this->pageTag)
                    array_push($rootFiles, $file);
                else 
                    array_push($dataFiles, $file);
            }
        }
        return ['root' => $rootFiles, 'data' => $dataFiles];
    }

    protected function createRootPage(array $files)
    {
        $len = count($files);
        if ($len == 0) return;
        if ($len == 1) return PageContent::loadFromFile($files[0]);
        else 
        {
            $content = PageContent::loadFromFile($files[0]);
            for($i = 1; $i < $len; $i++)
            {
                $additional = PageContent::loadFromFile($files[$i]);
                $content->mergeWith($additional);
            }
            return $content;
        }
    }

    protected function createDataPages(array $files)
    {
        $data = [];
        foreach($files as $file)
        {
            $name = $file->filename();
            $page = PageContent::loadFromFile($file);
            $data[$name] = $page;
        }
        return $data;
    }

    static public function build(string $pageTag, array $pageFiles)
    {
        $builder = new static($pageTag, $pageFiles);
        return $builder->buildPageContent();
    }
}