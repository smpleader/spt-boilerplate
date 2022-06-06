<?php
/**
 * SPT software - homeController
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 * 
 */

namespace App\plugins\starter\controllers;

use SPT\MVC\JDIContainer\MVController;

class Starter extends MVController 
{
    public function demo()
    {
        // write your code here
        $this->app->set('format', 'html');
        $this->app->set('layout', 'demo');
    }
}