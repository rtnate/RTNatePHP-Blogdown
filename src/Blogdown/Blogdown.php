<?php 

namespace RTNatePHP\Blogdown;

use RTNatePHP\Blogdown\Core\PageLoader;
use RTNatePHP\Blogdown\Core\SiteLoader;
use RTNatePHP\Util\Arr;

class Blogdown 
{
    static public $instance = null;
    protected $contentDirectory = '';
    protected $layoutDirectory = '';
    protected $useSafeMode = true;
    protected $siteData = [];

    static public function create(array $config = [])
    {
        if (static::$instance == null) 
        {
            static::$instance = new static($config);
        }
        return static::$instance;
    }

    protected function __construct(array $config = [])
    {
        $this->loadConfiguration($config);
    }

    protected function loadConfiguration(array $config)
    {
        $this->siteData = Arr::get($config, 'site_data', []);
        $this->rootDirectory = rtrim(Arr::get($config, 'root_directory', ''), '/');
        $this->contentDirectory = ltrim(Arr::get($config, 'content_directory', ''), '/');
        $this->layoutDirectory = ltrim(Arr::get($config, 'layout_directory', ''), '/');
        $this->useSafeMode = Arr::get($config, 'use_safe_mode', true);
    }

    public function getSiteLoader()
    {
        $options = ['site_data', $this->siteData, 'use_safe_mode', $this->useSafeMode];
        return new SiteLoader($this->getContentDirectory(), $options);
    }

    public function getPageLoader()
    {
        $options = ['use_safe_mode', $this->useSafeMode];
        return new PageLoader($this->getSiteLoader(), $options);
    }

    public function getContentDirectory()
    {
        return $this->rootDirectory.'/'.$this->contentDirectory;
    }

}