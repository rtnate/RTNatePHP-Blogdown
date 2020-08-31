<?php 

namespace RTNatePHP\Blogdown\Bridge;

use Psr\Container\ContainerInterface;
use RTNatePHP\BasicApp\BasicApp;
use RTNatePHP\Blogdown\Blogdown;
use RTNatePHP\Blogdown\Core\PageLoader;
use RTNatePHP\Blogdown\Core\SiteLoader;

class BlogdownDI
{
    static public function providers()
    {
        $test = '';
        return [
            Blogdown::class => function(BasicApp $app)
            {
                if (Blogdown::$instance != null) return Blogdown::$instance;
                else 
                {
                    $config = $app->config('blogdown', []);
                    $config['root_directory'] = $app->path('/');
                    $config['site'] = $app->config('site', []);
                    return Blogdown::create($config);
                }
            },
            SiteLoader::class => \DI\Factory([Blogdown::class, 'getSiteLoader']),
            PageLoader::class => \DI\Factory([Blogdown::class, 'getPageLoader'])
        ];
    }
}