<?php 

namespace RTNatePHP\Blogdown\Files;

class PHPFile extends BaseFile
{
    protected $file_data = [];
    protected $file_content = '';

    public function __construct(string $filename)
    {
        parent::__construct($filename, FileType::PHP());
    }

    protected function parse(string $filename)
    {
        try
        {
            $contents = include($filename);
            if (is_array($contents))
            {
                $this->file_data = $contents;
                $this->file_content = '';
            }
            else if (is_string($contents))
            {
                $this->file_data = [];
                $this->file_content = $contents;
            }
            else 
            {
                $this->file_data = [];
                $this->file_type = FileType::INVALID();
                $this->file_content = '';
            }
        }
        catch(\Throwable $e)
        {
            $this->file_data = [];
            $this->file_content = '';
            $this->file_type = FileType::INVALID();
        }
    }

    public function hasFrontmatter(): bool
    {
        return false;
    }

    public function content(): string
    {
        return $this->file_content;
    }

    public function data(): array
    {
        return $this->file_data;
    }

    public function fileContents(): string
    {
        if ($this->file_type->equals(FileType::INVALID())) return '';
        if ($this->file_type->equals(FileType::UNKNOWN())) return '';
        return file_get_contents($this->file_name);
    }
}