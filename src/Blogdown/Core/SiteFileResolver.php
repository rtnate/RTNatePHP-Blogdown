<?php 

namespace RTNatePHP\Blogdown\Core;

class SitefileResolver extends FileResolver
{
    public function __construct(string $contentFileDirectory)
    {
        parent::__construct($contentFileDirectory);
    }

    public function loadFiles()
    {
        $files = $this->fetchSiteFiles();
        return ['route' => 'site', 'files' => $files];
    }

    protected function fetchSiteFiles()
    {
        $dir = $this->baseDir;
        if (is_dir($dir.'/_data'))
        {
            return $this->fetchFilesFromDirectory($dir.'/_data', '');
        }
    }

}