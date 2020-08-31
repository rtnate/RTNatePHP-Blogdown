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
        switch($type)
        {
            case BaseFile::FILE_TYPE_TEXT:
                return new InvalidFile($filename);
            case BaseFile::FILE_TYPE_HTML:
                return new HTMLFile($filename);
            case BaseFile::FILE_TYPE_MARKDOWN:
                return new MarkdownFile($filename);
            case BaseFile::FILE_TYPE_PHP:
                return BaseFile::FILE_TYPE_PHP;
            case BaseFile::FILE_TYPE_JSON:
                return new JsonFile($filename);
            case BaseFile::FILE_TYPE_XML:
                return new InvalidFile($filename);
            case BaseFile::FILE_TYPE_YAML:
                return new YamlFile($filename);
            case BaseFile::FILE_TYPE_UNKNOWN:
            case BaseFile::FILE_TYPE_INVALID:
            default:
                return new InvalidFile($filename);
        }
    }

    static protected function DetectFileType(string $filename): string
    {
        if (file_exists($filename))
        {
            if (is_dir($filename)) return BaseFile::FILE_TYPE_INVALID;
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $ext = strtolower($ext);
            switch($ext) 
            {
                case 'html':
                    return BaseFile::FILE_TYPE_HTML;
                case 'md': 
                    return BaseFile::FILE_TYPE_MARKDOWN; 
                case 'php': 
                    return BaseFile::FILE_TYPE_PHP; 
                case 'json': 
                    return BaseFile::FILE_TYPE_JSON; 
                case 'xml': 
                    return BaseFile::FILE_TYPE_XML; 
                case 'yaml': 
                case 'yml': 
                    return BaseFile::FILE_TYPE_YAML;
            }
            $test = file_get_contents($filename, false, null. 0, 128);
            if ($test === false) return BaseFile::FILE_TYPE_INVALID;
            else return BaseFile::FILE_TYPE_TEXT;
        }
        else return BaseFile::FILE_TYPE_INVALID;
    }
}