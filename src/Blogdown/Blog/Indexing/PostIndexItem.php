<?php

namespace RTNatePHP\Blogdown\Blog\Indexing;

use Carbon\Carbon;
use Carbon\Traits\Date;
use RTNatePHP\Blogdown\Blog\Support\SearchPatterns;
use RTNatePHP\Blogdown\Indexing\PageIndexItem;
use SplFileInfo;

class PostIndexItem extends PageIndexItem
{
    protected $post_file_title = '';
    protected $post_date = '';
    protected $post_title;
    protected $tags;
    protected $categories;

    public function createFromFile(SplFileInfo $fileinfo)
    {
        $name = $fileinfo->getFilename();
        $matches = [];
        $validPostName = preg_match(SearchPatterns::$PostFormat, $name, $matches);
        if ($validPostName)
        {
            return new static($fileinfo->__toString());
        }
        else return null;
    }

    public function parse()
    {
        $data = $this->getFilenameData();
        $this->post_date = $data['date'];
        $this->post_file_title = $data['title'];
        $this->loadFrontMatter();
    }

    public function categories(): array 
    {

    }

    public function tags(): array
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

    protected function getFilenameData(): array 
    {
        $result = [];
        $filename = $this->file->getFilename();
        $pregMatches = [];
        preg_match(SearchPatterns::$PostFormat, $filename, $pregMatches);
        $year = $pregMatches[1];
        $month = (strlen($pregMatches[2]) < 2) ? '0'.$pregMatches[2] : $pregMatches[2];
        $day = (strlen($pregMatches[3]) < 2) ? '0'.$pregMatches[3] : $pregMatches[3];
        $result['date'] = Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
        $title_raw = $pregMatches[4];
        $result['title'] = trim($title_raw, '\n\r\t\v\0-');
        $result['ext'] = $pregMatches[5];
        return $result;
    }
}