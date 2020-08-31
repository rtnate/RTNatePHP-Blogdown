<?php 

namespace RTNatePHP\Blogdown\Core;

class FileResolver 
{
    protected $validExtensions = ['.txt', '.html', '.md', '.yaml', '.yml', '.csv', '.json'];

    protected $baseDir;

    public function __construct(string $contentFileDirectory)
    {
        $this->baseDir = $contentFileDirectory;
    }

    protected function fetchFilesFromDirectory(string $directory, string $identifier, bool $fetchData = false)
    {
        $searchPattern = $this->getSearchPattern();
        $mainFiles = $this->fetchMathingFiles($directory, $identifier, $searchPattern);
        if ($fetchData == true)
        {
            $dataFiles = [];
            if (is_dir($directory.'/_data'))
            {
                $dataFiles = $this->fetchMathingFiles($directory.'/_data', '', $searchPattern);
            }
            return array_merge($mainFiles, $dataFiles);
        }
        return $mainFiles;
    }

    protected function fetchMathingFiles(string $directory, string $identifier, string $pattern)
    {
        $search = $directory.'/'.$identifier.$pattern;
        return glob($search, GLOB_BRACE);
    }

    protected function tagDirectoryExists(string $directory, string $tag)
    {
        $search = $this->getTagDirectoryName($tag);
        if (is_dir($directory.'/'.$search)) return true;
        else return false;
    }

    protected function getFormattedRouteTag(string $routeTag)
    {
        return str_replace('-', '_', strtolower($routeTag));
    }

    protected function getTagDirectoryName(string $tag)
    {
        return "_".$tag;
    }

    protected function getSearchPattern()
    {
        $search = '*.{';
        foreach($this->validExtensions as $extension)
        {
            $output = '';
            $extension = ltrim($extension, '.');
            for ($i = 0; $i < strlen($extension); $i++)
            {
                $char = $extension[$i];
                $upper = strtoupper($char);
                $output .= "[$char$upper]";
            }
            $search .= $output.',';
        }
        $last = strlen($search) - 1;
        $search[$last] = '}';
        return $search;
    }
}