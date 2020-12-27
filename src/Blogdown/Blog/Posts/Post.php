<?php

namespace RTNatePHP\Blogdown\Blog\Posts;

use RTNatePHP\Blogdown\Files\FileLoader;

class Post 
{
    protected $post_file = null;

    protected $post_body = null;

    protected $post_date = null;

    protected $post_file_title = '';

    protected $post_categories = [];

    protected $post_tags = [];

    protected $post_data = [];

    /**
     * Constructs a new blog post using the provided file if supplied.
     * 
     * If $file is a valid string containing a valid post, the post is
     * constructed and populated using the supply file.
     * 
     * If an empty string is a supplied, a blank post is created.
     * 
     * If an invalid file is supplied, an invalid post is created.
     *
     * @param string $file
     */
    public function __construct(string $file = '')
    {
        if (!$file) $this->createEmptyPost();
        $file = FileLoader::loadFile($file);
    }

    protected function createEmtpyPost()
    {

    }
}