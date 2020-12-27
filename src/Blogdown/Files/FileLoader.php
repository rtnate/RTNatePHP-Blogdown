<?php 

namespace RTNatePHP\Blogdown\Files; 

class FileLoader
{
    static public function loadMany(string $directory, string $searchPattern = "*")
    {
        $result = [];
        $dir = rtrim($directory, "/");
        $files = glob($dir."/".$searchPattern);
        foreach ($files as $file)
        {
            $loaded = self::loadFile($file);
            array_push($result, $loaded);
        }
        return $result;
    }

    static public function loadFiles($files): array 
    {
        $results = [];
        if (!is_array($files)) $files = array($files);
        foreach($files as $file)
        {   
            $loaded = self::loadFile($file);
            array_push($results, $loaded);
        }
        return $results;
    }

    static public function loadFile(string $filename): FileInterface
    {
        $type = self::DetectFileType($filename);
        switch((string)$type)
        {
            case FileType::TEXT():
                return new TextFile($filename);
            case FileType::HTML():
                return new HTMLFile($filename);
            case FileType::MARKDOWN():
                return new MarkdownFile($filename);
            case FileType::PHP():
                return new PHPFile($filename);
            case FileType::JSON():
                return new JsonFile($filename);
            case FileType::XML():
                return new UnknownFile($filename);
            case FileType::YAML():
                return new YamlFile($filename);
            case FileType::UNKNOWN():
                return new UnknownFile($filename);
            default:
                return new InvalidFile($filename);
        }
    }

    static protected function DetectFileType(string $filename): string
    {
        return BaseFile::DetectFileType($filename);
    }
}