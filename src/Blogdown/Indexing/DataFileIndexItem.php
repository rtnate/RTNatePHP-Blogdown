<?php 

namespace RTNatePHP\Blogdown\Indexing;

class DataFileIndexItem extends FileIndexItem 
{
    public function parse()
    {

    }

    public function route(): string 
    {
        return '';
    }

    public function type(): string 
    {
        return 'data_file';
    }
}