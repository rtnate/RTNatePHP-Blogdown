<?php 

namespace RTNatePHP\Blogdown\Files;

use Symfony\Component\Yaml\Yaml;

class YamlFile extends BaseFile
{
    protected $file_data = [];

    public function __construct(string $filename)
    {
        parent::__construct($filename, FileType::YAML());
    }

    protected function parse(string $filename)
    {
        $contents = file_get_contents($filename);
        if ($contents === false)
        {
            $this->file_data = [];
            $this->file_type = FileType::INVALID();
        }
        else 
        {
            $this->parseYaml($contents);
        }
    }

    protected function parseYaml($contents)
    {
        try 
        {
            $data = Yaml::parse($contents);
            if (!is_array($data)) $this->file_data = [$data];
            else $this->file_data = $data;
        }
        catch(\Throwable $error)
        {
            $this->file_data = [];
            $this->file_type = FileType::INVALID();
        }
    }

    public function hasFrontmatter(): bool
    {
        return false;
    }

    public function content(): string
    {
        return '';
    }

    public function data(): array
    {
        return $this->file_data;
    }

    public function fileContents(): string
    {
        if ($this->file_type === FileType::INVALID()) return '';
        if ($this->file_type === FileType::UNKNOWN()) return '';
        return file_get_contents($this->file_name);
    }
}