<?php 

namespace RTNatePHP\Blogdown\Files;

abstract class BaseFile implements FileInterface
{
    const FILE_TYPE_TEXT= 'text';
    const FILE_TYPE_HTML = 'html';
    const FILE_TYPE_MARKDOWN = 'markdown';
    const FILE_TYPE_PHP = 'php';
    const FILE_TYPE_JSON = 'json';
    const FILE_TYPE_XML = 'xml';
    const FILE_TYPE_YAML = 'yaml';
    const FILE_TYPE_INVALID = 'invalid';
    const FILE_TYPE_UNKNOWN = 'unknown';

    protected $file_name;
    protected $file_exists = false;
    protected $file_type = self::FILE_TYPE_INVALID;

    protected function __construct(string $filename, string $file_type)
    {
        $this->file_name = $filename;
        if (file_exists($filename))
        {
            $this->file_exists = true;
            $this->file_type = $this->sanitizeFileType($file_type);
        }
        else 
        {
            $this->file_exists = false;
            $this->file_type = self::FILE_TYPE_INVALID;
        }
        if ($this->file_exists) $this->parse($filename);
    }

    protected function sanitizeFileType(string $file_type)
    {
        $type = strtolower($file_type);
        switch($type)
        {
            case self::FILE_TYPE_TEXT:
                return self::FILE_TYPE_TEXT;
            case self::FILE_TYPE_HTML:
                return self::FILE_TYPE_HTML;
            case self::FILE_TYPE_MARKDOWN:
                return self::FILE_TYPE_MARKDOWN;
            case self::FILE_TYPE_PHP:
                return self::FILE_TYPE_PHP;
            case self::FILE_TYPE_JSON:
                return self::FILE_TYPE_JSON;
            case self::FILE_TYPE_XML:
                return self::FILE_TYPE_XML;
            case self::FILE_TYPE_YAML:
                return self::FILE_TYPE_YAML;
            case self::FILE_TYPE_UNKNOWN:
                return self::FILE_TYPE_UNKNOWN;
            case self::FILE_TYPE_INVALID:
            default:
                    return self::FILE_TYPE_INVALID;
        }
    }

    abstract protected function parse(string $filename);

    public function valid(): bool
    {
        return $this->validFile;
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
        return $this->file_type;
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

}