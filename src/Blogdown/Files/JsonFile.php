<?php 

namespace RTNatePHP\Blogdown\Files;

class JsonFile extends BaseFile
{
    protected $file_data = [];

    public function __construct(string $filename)
    {
        parent::__construct($filename, self::FILE_TYPE_JSON);
    }

    protected function parse(string $filename)
    {
        $contents = file_get_contents($filename);
        if ($contents === false)
        {
            $this->file_data = [];
            $this->file_type = static::FILE_TYPE_INVALID;
        }
        else 
        {
            $data = json_decode($contents, true);
            if ($data === null)
            {
                $this->file_data = [];
                $this->file_type = static::FILE_TYPE_INVALID;
            }
            else $this->file_data = $data;
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
        if ($this->file_type === static::FILE_TYPE_INVALID) return '';
        if ($this->file_type === static::FILE_TYPE_UNKNOWN) return '';
        return file_get_contents($this->file_name);
    }
}