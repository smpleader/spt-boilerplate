<?php
/**
 * SPT software - homeController
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 * 
 */

namespace App\plugins\user\controllers;

use SPT\Util;
use SPT\MVC\JDIContainer\MVController;

class AdminController extends MVController 
{
    public function isLoggedIn($group = false)
    {
        if( !$this->user->get('id') )
        {
            $this->app->redirect(
                $this->router->url(
                    $this->config->redirect['notBackend']
                )
            );
        }

        if($group && $this->user->get('group') != $group)
        {
            $this->app->redirect(
                $this->router->url(
                    $this->config->redirect['notBackendGroup']
                )
            );
        }
    }
}