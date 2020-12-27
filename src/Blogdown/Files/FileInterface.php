<?php 

namespace RTNatePHP\Blogdown\Files;

interface FileInterface 
{
    public function isValid(): bool;

    public function hasFrontmatter(): bool;

    public function content(): string;

    public function data(): array;

    public function fileContents(): string;

    public function type(): string;

    public function fileType(): FileType;
    
    public function filename(): string;

    public function extension(): string;

    public function directory(): string;

    public function path(): string;

    public function info(): array;

    public function exists(): bool;
}