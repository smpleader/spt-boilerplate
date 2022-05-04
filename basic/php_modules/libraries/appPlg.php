<?php
/**
 * SPT software - Application
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic Application implement mvc
 * 
 */

namespace App\libraries; 

use SPT\App\JDIContainer\CMSApp; 
use SPT\Router\SitemapEntity;
use SPT\Router\Sitemap as Router;
use SPT\Plugin\PluginEntity;
use SPT\User\SPT\User;
use SPT\User\Instance as UserIns;
use SPT\User\SPT\UserEntity; 


class appPlg extends CMSApp
{
    public function getName(string $extra='')
    {
        return 'App\\'. $extra;
    }

    public function prepareRouter($config)
    {
        if($config->exists('endpoints'))
        {
            $sitePath = $config->exists('sitepath') ? $config->sitepath : '';

            $sitemapEntity = new \stdClass();

            if ($this->getContainer()->has('query'))
            {
                $sitemapEntity = new SitemapEntity($this->query);
                $this->getContainer()->set('SitemapEntity', $sitemapEntity);
            }
            $router = new Router($sitemapEntity, $config, $this->request);
            $this->getContainer()->share('router', $router, true);
        }
    }
     

    public function prepareUser()
    {
        $user = new UserIns( new User());
        $user->init([
            'session' => $this->session,
            'entity' => new UserEntity($this->query)
        ]);
        $this->getContainer()->share('user', $user, true);
    } 
}
