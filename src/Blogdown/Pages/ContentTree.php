<?php 

namespace RTNatePHP\Blogdown\Pages;

use Error;
use RTNatePHP\Blogdown\Files\FileInterface;
use RTNatePHP\Blogdown\Files\FileLoader;
use RTNatePHP\Util\Arr;

class ContentTree 
{
    protected $directory;
    protected $search;
    protected $pageDirectory;
    protected $rootPageJoined = null;
    protected $rootPages = [];
    protected $additionalPages = [];
    protected $parseSafe = false;

    public function __construct(string $root_directory, string $search_tag, bool $use_safe_mode = true)
    {
        $this->parseSafe = $use_safe_mode;
        //Trim Any Slashses 
        $this->directory = rtrim($root_directory, "/");
        $this->search = ltrim($search_tag, "/");
        $this->pageDirectory = $this->directory;
        $this->loadPages($this->directory, $this->search);
    }

    protected function loadPages(string $directory, string $search)
    {
        //If The Search Directory Does Not Exists, Throw an Exception
        if (!file_exists($directory))
        {
            throw new Error("Unable to load ContentTree, directory: $directory does not exist.");
        } 
        if (is_dir($directory.'/'.$search))
        {
            $this->pageDirectory = $directory.'/'.$search;
            $this->loadPagesFromDirectory($directory.'/'.$search);
        }
        else  
        {
            $this->loadPagesFromRoot($directory, $search);
        }
        $this->concatenateRootPage();
        $this->loadSubPages();
    }

    protected function loadPagesFromDirectory(string $directory)
    {
        $search = $this->search.'.*';
        
        $files = FileLoader::loadMany($directory, $search);

        $pages = $this->getPageContentFromFiles($files);

        $this->rootPages = array_merge($this->rootPages, $pages);
    }

    protected function loadPagesFromRoot(string $directory, string $search)
    {
        $search = $search.'.*';

        $files = FileLoader::loadMany($directory, $search);

        $pages = $this->getPageContentFromFiles($files);

        array_merge($this->rootPages, $pages);
    }

    protected function getPageContentFromFiles(array& $files)
    {
        $results = [];
        foreach($files as $file)
        {
            if (!($file instanceof FileInterface)) continue;
            else 
            {
                $page = PageContent::loadFromFile($file, $this->parseSafe);
                array_push($results, $page);
            }
        }
        return $results;
    }

    protected function concatenateRootPage()
    {
        $this->rootPageJoined = self::concatenatePages($this->rootPages);
    }

    protected function loadSubPages()
    {
        $subPages = [];
        if (!$this->rootPageJoined) return;
        $items = $this->rootPageJoined->items;
        if (!$items) return;
        if (!is_array($items)) $items = array($items);
        foreach($items as $item)
        {
            $search = $item.'.*';
            $files = FileLoader::loadMany($this->pageDirectory, $search);
            $itemPages = $this->getPageContentFromFiles($files);
            $itemPageJoined = self::concatenatePages($itemPages);
            $subPages[$item] = $itemPageJoined;
        }
        $this->rootPageJoined->addItems($subPages);
    }

    static protected function getConcatenatedData(array &$pages)
    {
        $pageData = [];
        foreach($pages as $page)
        {
            $data = $page->data();
            $pageData = array_merge_recursive($pageData, $data);
        }
        return $pageData;
    }

    static protected function sortPageBodiesByType(array& $pages)
    {
        $types = ['html' => [], 'markdown' => [], 'text' => [], 'unescaped' => []];
        foreach($pages as $page)
        {
            $body = $page->body();
            $type = $body->type();
            if ($type == PageBody::CONTENT_HTML) array_push($types['html'], $body);
            else if ($type == PageBody::CONTENT_MARKDOWN) array_push($types['markdown'], $body);
            else if ($type == PageBody::CONTENT_UNESCAPED) array_push($types['unescaped'], $body);
            else if ($type == PageBody::CONTENT_STRING) array_push($types['text'], $body);
        }
        return $types;
    }

    static protected function getConcatenatedBody(array &$pages)
    {
        $bodies = self::sortPageBodiesByType($pages);
        if (count($bodies['html']) > 0) return $bodies['html'][0];
        else if (count($bodies['markdown']) > 0) return $bodies['markdown'][0];
        else if (count($bodies['unescaped']) > 0) return $bodies['unescaped'][0];
        else if (count($bodies['text']) > 0) return $bodies['text'][0];
        else return new PageBody('', PageBody::CONTENT_STRING);
    }

    static protected function concatenatePages(array &$pages)
    {
        $data = self::getConcatenatedData($pages);
        $body = self::getConcatenatedBody($pages);
        return new PageContent($data['title'], $body, $data);
    }
}