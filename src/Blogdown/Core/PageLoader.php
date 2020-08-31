<?php 

namespace RTNatePHP\Blogdown\Core;

use RTNatePHP\Blogdown\Files\FileLoader;
use RTNatePHP\Blogdown\Pages\PageBuilder;
use RTNatePHP\Blogdown\Pages\PageContent;
use RTNatePHP\Blogdown\Pages\PageContentBuilder;

class PageLoader 
{
    protected $site;
    protected $baseDir;
    protected $content = null;
    protected $url = '';

    public function __construct(SiteLoader $siteLoader, array $options = [])
    {
        $this->site = $siteLoader;
        $this->baseDir = $siteLoader->getContentDirectory();
    }

    public function loadRoute(string $route)
    {
        $this->url = ($route == 'index' ? '': $route);
        $this->site->load();
        $routeFiles = $this->fetchRouteFiles($route);
        if (count($routeFiles['files']) > 0)
        {
            $loaded = $this->loadRouteFiles($routeFiles['files']);
            $this->content = $this->loadPageContent($routeFiles['tag'], $loaded);
        }
    }

    public function getPageData()
    {
        return
        [
            'site' => $this->site->getSiteData(),
            'page_route' => $this->url,
            'page' => $this->getContent()
            
        ];
    }

    public function getContent()
    {
        return $this->content;
    }

    public function hasContent()
    {
        return ($this->content != null);
    }

    protected function fetchRouteFiles(string $route)
    {
        $resolver = new RouteFileResolver($this->baseDir);
        return $resolver->loadFilesForRoute($route);
    }

    protected function loadRouteFiles(array $files)
    {
        return FileLoader::loadFiles($files);
    }

    protected function loadPageContent(string $pageTag, array $files)
    {
        return PageContentBuilder::build($pageTag, $files);
    }
}