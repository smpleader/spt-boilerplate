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

use SPT\Middleware\Dispatcher as MW;

class UserGroup extends Admin 
{
    public function list()
    {
        $this->isLoggedIn();
        
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.userGroup.list');
        $this->app->set('page', 'backend');
    }

    public function detail()
    {
        $this->isLoggedIn();
        $id = (int) $this->app->get('object_id', '');
        
        $this->app->set('layout', 'backend.userGroup.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    } 

    public function add()
    {
        $this->isLoggedIn();
        
        $try = MW::fire('validation', ['ValidateGroup'], []);
        if (!$try)
        {
            $msg = $this->session->get('validate', '');
            $this->app->redirect(
                $this->router->url('admin/user-group'), $msg
            );
        }
        // TODO: validate new add
        $status = $this->request->post->get('status', '', 'string') == 'active' ? 1 : 0;
        $newId =  $this->GroupEntity->add([
            'name' => $this->request->post->get('name', '', 'string'),
            'description' => $this->request->post->get('description', '', 'string'),
            'access' => json_encode($this->request->post->get('access', '', 'array')),
            'status' => $status,
            'created_by' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s')
        ]);
        
        if( !$newId )
        {
            $msg = 'Error: Save Failed';
            $this->session->set('flashMsg', $msg);
            $this->app->redirect(
                $this->router->url('admin/user-group'), $msg
            );
        }
        else
        {
            $this->app->redirect(
                $this->router->url('admin/user-groups'), 'Save Successfully'
            );
        }
    }

    public function update()
    {
        $sth = $this->validateId(); 
        
        if( is_array($sth) )
        {
            // publishment
            $count = 0;
            $action = $this->request->post->get('published', '', 'string');

            foreach($sth as $id)
            {
                if( $this->GroupEntity->toggleActive($id, $action) )
                {
                    $count++;
                }
           }

            $this->app->redirect( $this->router->url('admin/user-groups'), $count.' changed record(s)');

        }
        elseif( is_numeric($sth) )
        {   
            $try = MW::fire('validation', ['ValidateGroup'], []);
            if (!$try)
            {
                $msg = $this->session->get('validate', '');
                $this->app->redirect(
                    $this->router->url('admin/user-group/'.$sth), $msg
                );
            }

            $status = $this->request->post->get('status', '', 'string') == 'active' ? 1 : 0;
            $user = [
                'name' => $this->request->post->get('name', '', 'string'),
                'description' => $this->request->post->get('description', '', 'string'),
                'access' => json_encode($this->request->post->get('access', '', 'array')),
                'status' => $status,
                'modified_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s'),
                'id' => $sth,
            ];
            $try = $this->GroupEntity->update( $user );
    
            $msg = $try ? 'Edit Successfully' : 'Edit Failed';
    
            if ($try)
            {
                $this->app->redirect(
                    $this->router->url('admin/user-groups'), $msg
                );
            }
            else
            {
                $msg = 'Error: Save Failed';
                $this->session->set('flashMsg', $msg);
                $this->app->redirect(
                    $this->router->url('admin/user-group/'.$sth), $msg
                );
            }
            
        }

        $this->app->redirect(
            $this->router->url('admin/user-groups'), 'Error: Invalid request'
        );
    }

    public function delete()
    {
        $sth = $this->validateID();
        
        $count = 0;

        if( is_array($sth))
        {
            foreach($sth as $id)
            {
                if( $this->GroupEntity->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($sth) )
        {
            if( $this->GroupEntity->remove($sth ) )
            {
                $count++;
            }
        }  
        $this->app->redirect( $this->router->url('admin/user-groups'), $count.' deleted record(s)');
    }

    public function validateID()
    {
        $this->isLoggedIn();
        $id = (int) $this->app->get('object_id', '');
        if(empty($id) && !$id)
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->app->redirect(
                $this->router->url('admin/user-groups'), 'Invalid user group'
            );
        }

        return $id;
    }
}
