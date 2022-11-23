<?php
/**
 * SPT software - homeController
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 * 
 */

namespace App\plugins\restapi\controllers;

use SPT\MVC\JDIContainer\MVController;

class Home extends MVController 
{
    public function testGet()
    {
        $this->app->set('format', 'json');
        $this->set('method', 'GET');
        $this->set('get_variables', $this->app->request->get->getAll());
    }

    public function testPost()
    {
        $this->app->set('format', 'json');
        $this->set('method', 'POST');
        $try = $this->app->request->post->getAll();
        if(empty($try))
        {
            $try = $this->app->request->json->getAll();
        }
        $this->set('post_variables', $try);
    }

    public function testPut()
    {
        $this->app->set('format', 'json');
        $this->set('method', 'PUT');
        $try = $this->app->request->post->getAll();
        if(empty($try))
        {
            $try = $this->app->request->json->getAll();
        }
        $this->set('put_variables', $try);
    }

    public function testDelete()
    {
        $this->app->set('format', 'json');
        $this->set('method', 'Delete');
        $try = $this->app->request->post->getAll();
        if(empty($try))
        {
            $try = $this->app->request->json->getAll();
        }
        $this->set('delete_variables', $try);
    }

    public function display()
    {
        $this->set('url', $this->app->router->url('test'));
        $this->app->set('format', 'html');
        $this->app->set('layout', 'home');
    }
}