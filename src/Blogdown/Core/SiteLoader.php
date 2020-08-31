<?php 

namespace RTNatePHP\Blogdown\Core;

use RTNatePHP\Blogdown\Files\FileInterface;
use RTNatePHP\Blogdown\Files\FileLoader;
use RTNatePHP\Util\Arr;

class SiteLoader 
{
    protected $baseDir = '';
    protected $siteData = [];

    public function __construct(string $contentDirectory, array $options = [])
    {
        $this->baseDir = $contentDirectory;
        $data = Arr::get($options, 'site_data', '');
        if (!is_array($data)) $data = array($data);
        $this->siteData = array_merge($this->siteData, $data);
    }

    public function getContentDirectory(): string 
    {
        return $this->baseDir;
    }

    public function load()
    {
        $siteFiles = $this->fetchSiteFiles();
        if (!$siteFiles) $siteFiles = [];
        $loaded = $this->loadSiteFiles($siteFiles['files']);
        $data = $this->parseFileData($loaded);
        $this->siteData = array_merge_recursive($this->siteData, $data);
    }

    public function getSiteData(): array
    {
        return $this->siteData;
    }

    protected function fetchSiteFiles()
    {
        $resolver = new SiteFileResolver($this->baseDir);
        return $resolver->loadFiles();
    }

    protected function loadSiteFiles(array $files)
    {
       return FileLoader::loadFiles($files);
    }

    protected function parseFileData(array $files)
    {
        $parsedData = [];
        foreach($files as $file)
        {
            if (!($file instanceof FileInterface)) continue;
            $fileData = $file->data();
            $parsedData = array_merge_recursive($fileData);
        }
        return $parsedData;
    }
}