<?php 

namespace RTNatePHP\Blogdown\Core;

class RouteFileResolver extends FileResolver
{
    protected $baseDir;

    public function __construct(string $contentFileDirectory)
    {
        parent::__construct($contentFileDirectory);
    }

    public function loadFilesForRoute(string $route)
    {
        $route = $this->trimAndCleanRouteString($route);
        //An Empty or '/' Route is Aliased to Index
        if ($route == '') $route = 'index';
        $routeFiles = $this->fetchRouteFiles($route);
        return ['route' => $route, 'tag' => $routeFiles['tag'], 'files' => $routeFiles['files']];
    }

    protected function trimAndCleanRouteString(string $route)
    {
        //Trim Whitespace
        $route = trim($route);
        //Trim Leading Slash 
        $route = ltrim($route, '/');
        return $route;
    }

    protected function fetchRouteFiles(string $route)
    {
        $routeParts = $this->getRouteParts($route);
        if (count($routeParts) == 1) 
        {
            $tag = $this->getFormattedRouteTag($routeParts[0]);
            $files = $this->findFiles($this->baseDir, $tag);
            return ['tag' => $tag, 'files' => $files];
        }
        else 
        {
            return $this->findFilesDeep($this->baseDir, $routeParts);
        }
    }

    protected function findFilesDeep(string $directory, array $routeParts)
    {
        $len = count($routeParts);
        $pathParts = array_slice($routeParts, 0, $len - 1);
        $tag = $this->getFormattedRouteTag($routeParts[$len - 1]);
        $path = implode('/', $pathParts);
        $files = $this->findFiles($directory.'/'.$path, $tag);
        return ['tag' => $tag, 'files' => $files];
    }

    protected function getRouteParts(string $route)
    {
        return explode('/', $route);
    }

    protected function findFiles(string $directory, string $tag)
    {
        if ($this->tagDirectoryExists($directory, $tag))
        {
            $tagDirectory = $this->getTagDirectoryName($tag);
            $searchDirectory = $directory.'/'.$tagDirectory;
            return $this->fetchFilesFromDirectory($searchDirectory, $tag, true);
        }
        else 
        {
            return $this->fetchFilesFromDirectory($directory, $tag, false);
        }
    }

}