<?php
/**
 * SPT software - Core plugin
 * 
 * @project: https://github.com/smpleader/spt-boilerplate
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 * 
 */

namespace App\plugins\sitemap;

use SPT\App\Instance as AppIns;
use Joomla\DI\Container;
use SPT\Plugin\CMS as PluginAbstract;
use SPT\Router\SitemapEntity;
use SPT\Dispatcher;

class plugin extends PluginAbstract
{ 
    public function register()
    {
        return [
            'models' => [
                'alias' => [
                    'App\plugins\sitemap\models\SitemapModel' => 'SitemapModel',
                ]
            ],
            'viewmodels' => [
                'alias' => [
                    'App\plugins\sitemap\viewmodels\SitemapsVM' => 'SitemapsVM',
                    'App\plugins\sitemap\viewmodels\SitemapPaginationVM' => 'SitemapPaginationVM',
                ],
            ],
        ];
    }

    public function registerRouter()
    {
        return [
            'admin' => [
                'sitemaps'=>[
                    'title' => 'Sitemap Manager',
                    'fnc' => [
                        'get' => 'sitemap.sitemap.list',
                        'post' => 'sitemap.sitemap.list',
                    ], 
                    'object' => 'sitemap',
                    'permission' => [
                        'PermissionKey' => 'sitemap_manager',
                    ],
                    'page' => 'home',
                ],
            ],
        ];
    }
    // hook in manage

    public function getInfo()
    {
        return [
            'title' => 'Plugin for sitemap',
            'name' => 'sitemap',
            'version' =>  '0.1',
            'schema_version' =>  '0.1',
        ];
    }
    
    public function getRightAccess()
    {
        return ['sitemap_manager'];
    }
}
