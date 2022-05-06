<?php
/**
 * SPT software - homeController
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 * 
 */

namespace App\plugins\sitemap\controllers;

class Sitemap extends Admin 
{
    public function list()
    {
        $this->isLoggedIn();
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.sitemap.list');
    }

    public function detail()
    {
    } 

    public function add()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
    }

    public function validateID()
    {
        $this->isLoggedIn();

        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->app->redirect(
                $this->router->url('admin/users'), 'Invalid user'
            );
        }

        return $id;
    }
}
