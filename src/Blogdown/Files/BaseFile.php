<?php 

namespace RTNatePHP\Blogdown\Files;

use RTNatePHP\Blogdown\Files\FileType;

abstract class BaseFile implements FileInterface
{

    protected $file_name;
    protected $file_exists = false;
    protected $file_type = null;
    protected $loaded = false;

    protected function __construct(string $filename, string $file_type)
    {
        $this->file_type = FileType::INVALID();
        $this->file_name = $filename;
        if (file_exists($filename))
        {
            $this->file_exists = true;
            $this->file_type = $this->sanitizeFileType($file_type);
        }
        else 
        {
            $this->file_exists = false;
            $this->file_type = FileType::INVALID();
        }
        if ($this->file_exists) $this->parse($filename);
    }

    protected function sanitizeFileType(string $file_type)
    {
        $type = strtolower($file_type);
        return new FileType($type);
        if (!$type) 
        {
            return FileType::Invalid();
        }
    }

    abstract protected function parse(string $filename);

    public function isValid(): bool
    {
        if ($this->file_type === null) return false;
        if (!$this->file_exists) return false;
        if ($this->file_type->equals('INVALID')) return false;
        else return true;
    }

    public function hasFrontmatter(): bool
    {
        return false;
    }

    abstract public function content(): string;

    abstract public function data(): array;

    abstract public function fileContents(): string;

    public function type(): string
    {
        return (string)$this->fileType();
    }

    public function fileType(): FileType
    {
        if ($this->file_type === null) return FileType::INVALID();
        else return $this->file_type;
    }

    public function filename(): string
    {
        return pathinfo($this->file_name, PATHINFO_FILENAME);
    }

    public function extension(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    public function basename(): string 
    {
        return pathinfo($this->file_name, PATHINFO_BASENAME);
    }

    public function directory(): string
    {
        return pathinfo($this->file_name, PATHINFO_DIRNAME);
    }

    public function path(): string
    {
        return $this->file_name;
    }

    public function info(): array
    {
        return pathinfo($this->file_name);
    }

    public function exists(): bool 
    {
        return $this->file_exists;
    }

    public function load()
    {
        try 
        {
            $this->parse($this->file_name);
            $this->loaded = true;
        }
        catch(\Throwable $e)
        {
            throw new FileException('Error parsing file.', $this->file_name, $e);
        }
    }

    static public function DetectFileType(string $filename): string
    {
        if (file_exists($filename))
        {
            if (is_dir($filename)) return FileType::INVALID();
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $ext = strtolower($ext);
            switch($ext) 
            {
                case 'html':
                    return FileType::HTML();
                case 'md': 
                    return FileType::MARKDOWN(); 
                case 'php': 
                    return FileType::PHP(); 
                case 'json': 
                    return FileType::JSON(); 
                case 'xml': 
                    return FileType::XML(); 
                case 'yaml': 
                case 'yml': 
                    return FileType::YML();
                case 'txt':
                    return FileType::TEXT();
;
            }
            $test = file_get_contents($filename, false, null. 0, 128);
            if ($test === false) return FileType::UNKNOWN();
            else return FileType::TEXT();
        }
        else return FileType::INVALID();
    }
}