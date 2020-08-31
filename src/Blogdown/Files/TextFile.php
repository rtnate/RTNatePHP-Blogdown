<?php 

namespace RTNatePHP\Blogdown\Files;

use Spatie\YamlFrontMatter\YamlFrontMatter;

class TextFile extends BaseFile
{
    protected $file_contents = '';
    protected $front_matter = [];

    public function __construct(string $filename, string $file_type = self::FILE_TYPE_TEXT)
    {
        parent::__construct($filename, $file_type);
    }

    protected function parse(string $filename)
    {
        $contents = file_get_contents($filename);
        if ($contents === false)
        {
            $this->file_contents = '';
            $this->file_type = static::FILE_TYPE_INVALID;
        }
        else 
        {
            $this->parseFrontMatter($contents);
        }
    }

    protected function parseFrontMatter(string $contents)
    {
        $parsed = YamlFrontMatter::parse($contents);
        $this->front_matter = $parsed->matter();
        $this->file_contents = $parsed->body();
    }

    public function hasFrontmatter(): bool
    {
        return (count($this->front_matter) > 0);
    }

    public function content(): string
    {
        return $this->file_contents;
    }

    public function data(): array
    {
        return $this->front_matter;
    }

    public function fileContents(): string
    {
        if ($this->file_type === static::FILE_TYPE_INVALID) return '';
        if ($this->file_type === static::FILE_TYPE_UNKNOWN) return '';
        return file_get_contents($this->file_name);
    }
}