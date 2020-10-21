<?php 

namespace RTNatePHP\Blogdown\Indexing;

use SplFileInfo;

interface IndexInterface 
{
    public function parent(): IndexInterface;

    public function children(): array;

    public function path(): string;

    public function filename(): string;

    public function filePath(): string;

    public function basename(): string;

    public function associated(): array;

    public function exists(): bool;

    public function info(): SplFileInfo;

    public function toArray(): array;

    public function route(): string;

    public function type(): string;
}