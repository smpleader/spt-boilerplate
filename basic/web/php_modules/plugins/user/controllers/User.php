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

class User extends Admin
{
    public function gate()
    {
        if( $this->user->get('id') )
        {
            $this->app->redirect(
                $this->router->url('admin/users')
            );
        }
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.user.login');
        $this->app->set('page', 'backend-full');
    }

    public function login()
    {
        $unactive = $this->session->get('unactiveUser', '');
        if ($unactive)
        {
            $now = strtotime('now');
            $timeLock = (int) $this->session->get('timeLock', '');
            $tmp = $now - $timeLock;
            if ($tmp < 300)
            {
                $mini = 5 - floor($tmp / 60);
                $msg = 'Error: You tried to login too much, please try again in '. $mini .' minutes';
                $this->app->redirect(
                    $this->router->url('admin/login'), $msg
                );
            }
            else
            {
                $this->session->set('unactiveUser', false);
                $this->session->set('timeLock', '');
                $this->session->set('countLoginFail', 0);
            }
        }

        $result = $this->user->login(
            $this->request->post->get('username', '', 'string'),
            $this->request->post->get('password', '', 'string')
        );

        if ( $result )
        {
            $this->session->set('timeLock', '');
            $this->session->set('countLoginFail', 0);
            $this->session->set('unactiveUser', '');

            if($result['status'] != 1) 
            {
                $this->session->set('flashMsg', 'Error: User has been block');
                $this->user->logout();
                $this->app->redirect(
                    $this->router->url('admin/login')
                );
            }
            else
            {
                $this->app->redirect(
                    $this->router->url('admin/sitemaps'), 'Hello!!!'
                );
            }
        }
        else
        {
            $this->session->set('timeLock', strtotime('now'));
            $count =(int) $this->session->get('countLoginFail', 0);
            $count++;
            $this->session->set('countLoginFail', $count);
            if ($count > 5)
            {
                $this->session->set('unactiveUser', true);
                $msg = 'Error: You tried to login too much, please try again in 5 minutes';
            }
            else
            {
                $msg = 'Error: You tried '. $count .' time. The username or password is incorrect, please input again';
            }

            $this->session->set('flashMsg', $msg);
            $this->app->redirect(
                $this->router->url('admin/login')
            );
        }
    }

    public function detail()
    {
        $this->isLoggedIn();

        $id = (int) $this->app->get('object_id', '');

        $existUser = $this->UserEntity->findByPK($id);
        if(!empty($id) && !$existUser) {
            $this->app->redirect(
                $this->router->url('admin/users'), "Invalid user"
            );
        }

        $this->app->set('layout', 'backend.user.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function list()
    {
        $this->isLoggedIn();
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.user.list');
    }

    public function logout()
    {
        $this->user->logout();
        $this->app->redirect(
            $this->router->url('admin/login'), 'Bye Bye'
        );
    }

    public function add()
    {
        $this->isLoggedIn();
        $try = MW::fire('validation', ['ValidateUser'], []);
        if (!$try)
        {
            $msg = $this->session->get('validate', '');
            $this->app->redirect(
                $this->router->url('admin/user/'), $msg
            );
        }

        if($_FILES['avatar']['name']) {
            $uploader = $this->file->setOptions([
                'overwrite' => true,
                'targetDir' => MEDIA_PATH
            ]);
    
            // TODO: create dynamice fieldName for file
            if(file_exists(MEDIA_PATH. $_FILES['avatar']['name'])) {
                $_FILES['avatar']['name'] = round(microtime(true)).'-'.$_FILES['avatar']['name'];
            }

            if( false === $uploader->upload($_FILES['avatar']) )
            {
                $this->app->redirect(
                    $this->router->url('admin/user/'), 'Upload avatar fail'
                );
            }
            
        }

        //check confirm password
        if($this->request->post->get('password', '') != $this->request->post->get('confirm_password', ''))
        {
            $this->app->redirect(
                $this->router->url('admin/user/'), 'Error: Confirm Password Failed'
            );
        }

        $avatar = $_FILES['avatar']['name'] ? $_FILES['avatar']['name'] : '';
        // TODO: validate new add
        $newId =  $this->UserEntity->add([
            'name' => $this->request->post->get('name', '', 'string'),
            'username' => $this->request->post->get('username', '' , 'string'),
            'email' => $this->request->post->get('email', '' , 'string'),
            'password' => md5($this->request->post->get('password', '')),
            'avatar' => $avatar,
            'status' => $this->request->post->get('status', '') == "" ? 0 : 1,
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
                $this->router->url('admin/user/'), $msg
            );
        }
        else
        {
            $this->app->redirect(
                $this->router->url('admin/users'), 'Save Successfully'
            );
        }
    }

    public function update()
    {
        $ids = $this->validateID(); 
        
        // TODO valid the request input

        if( is_array($ids) && $ids != null)
        {
            // publishment
            $count = 0; 
            $action = $this->request->post->get('published', '', 'string');
        
            if (in_array($this->user->get('id'), $ids) && $action == 'unactive')
            {
                $this->app->redirect(
                    $this->router->url('admin/users'), 'Error: You cannot unactivate your account'
                );
            }

            foreach($ids as $id)
            {
                $toggle = $this->UserEntity->togglePublishment($id, $action);
                $count++;
            }
            $this->app->redirect(
                $this->router->url('admin/users'), $count.' changed record(s)'
            );
        }
        if(is_numeric($ids) && $ids)
        {

            $try = MW::fire('validation', ['ValidateUser'], []);
            if (!$try)
            {
                $msg = $this->session->get('validate', '');
                $this->app->redirect(
                    $this->router->url('admin/user/'. $ids), $msg
                );
            }

            $password = $this->request->post->get('password', '');
            $repassword = $this->request->post->get('confirm_password', '');
            if($password) {
                $user['password'] = $this->request->post->get('password', '');
            }
            if($password == $repassword) 
            {
                if($_FILES['avatar']['name']) {
                    $uploader = $this->file->setOptions([
                        'overwrite' => true,
                        'targetDir' => MEDIA_PATH
                    ]);
            
                    //Delete file in source
                    $data = $this->UserEntity->findByPK($ids);
                    if($data['avatar']) {
                        if (file_exists(MEDIA_PATH .$data['avatar'])) unlink(MEDIA_PATH .$data['avatar']);
                    }

                    // TODO: create dynamice fieldName for file
                    if(file_exists(MEDIA_PATH. $_FILES['avatar']['name'])) {
                        $_FILES['avatar']['name'] = round(microtime(true)).'-'.$_FILES['avatar']['name'];
                    }

                    if( false === $uploader->upload($_FILES['avatar']) )
                    {
                        $this->app->redirect(
                            $this->router->url('admin/user'. $ids), 'Upload avatar fail'
                        );
                    }

                    $user = [
                        'name' => $this->request->post->get('name', '', 'string'),
                        'username' => $this->request->post->get('username', '' , 'string'),
                        'email' => $this->request->post->get('email', '', 'string'),
                        'avatar' => $_FILES['avatar']['name'],
                        'status' => $this->request->post->get('status', '') == "" ? 0 : 1,
                        'modified_by' => $this->user->get('id'),
                        'modified_at' => date('Y-m-d H:i:s'),
                        'id' => $ids,
                    ];
                }
                else 
                {
                    if($this->request->post->get('delete', '') == 1) 
                    {
                        $user = [
                            'name' => $this->request->post->get('name', '', 'string'),
                            'username' => $this->request->post->get('username', '' , 'string'),
                            'email' => $this->request->post->get('email', '', 'string'),
                            'avatar' => "",
                            'status' => $this->request->post->get('status', '') == "" ? 0 : 1,
                            'modified_by' => $this->user->get('id'),
                            'modified_at' => date('Y-m-d H:i:s'),
                            'id' => $ids,
                        ];
                    }
                    else
                    {
                        $user = [
                            'name' => $this->request->post->get('name', '', 'string'),
                            'username' => $this->request->post->get('username', '' , 'string'),
                            'email' => $this->request->post->get('email', '', 'string'),
                            'status' => $this->request->post->get('status', '') == "" ? 0 : 1,
                            'modified_by' => $this->user->get('id'),
                            'modified_at' => date('Y-m-d H:i:s'),
                            'id' => $ids,
                        ];
                    }
                }
            }
            else
            {
                $this->app->redirect(
                    $this->router->url('admin/user/'.$ids), 'Error: Confirm Password Failed'
                );
            }

            $passwrd =  $this->request->post->get('password','');
            if($passwrd) $user['password'] = md5($passwrd);
            
            $try = $this->UserEntity->update( $user );

            if($try) 
            {
                $this->app->redirect(
                    $this->router->url('admin/users'), 'Edit Successfully'
                );
            }
            else
            {
                $msg = 'Error: Save Failed';
                $this->session->set('flashMsg', $msg);
                $this->app->redirect(
                    $this->router->url('admin/user/'. $ids), $msg
                );
            }
        }
    }

    public function validateUserId()
    {
        $this->isLoggedIn();
        $id = (int) $this->app->get('object_id', '');
        if(empty($id))
        {
            $this->app->redirect(
                $this->router->url('admin/users'),
                'Invalid user'
            );
        }

        $existUser = $this->UserEntity->findByPK($id);
        if(!$existUser) {
            $this->app->redirect(
                $this->router->url('admin/users'), "Invalid user"
            );
        }

        return $id;
    }

    public function delete()
    {
        $userID = $this->validateID();
        
        $count = 0;
        if( is_array($userID))
        {
            foreach($userID as $id)
            {
                if( $id == $this->user->get('id') )
                {
                    $this->app->redirect(
                        $this->router->url('admin/users'),
                        'Error: You can\'t delete yourself.'
                    );
                }

                //Delete file in source
                $data = $this->UserEntity->findByPK($id);

                if( $this->UserEntity->remove( $id ) )
                {
                    if($data['avatar']) {
                        if (file_exists(MEDIA_PATH .$data['avatar'])) unlink(MEDIA_PATH .$data['avatar']);
                    }
                    $count++;
                }
            }
        }
        elseif( is_numeric($userID) )
        {
            if( $userID === $this->user->get('id') )
            {
                $this->app->redirect(
                    $this->router->url(),
                    'Error: You can\'t delete yourself.'
                );
            }
            //Delete file in source
            $data = $this->UserEntity->findByPK($userID);
            if( $this->UserEntity->remove($userID ) )
            {
                if($data['avatar']) {
                    if (file_exists(MEDIA_PATH .$data['avatar'])) unlink(MEDIA_PATH .$data['avatar']);
                }
                $count++;
            }
        }  
        

        $this->app->redirect(
            $this->router->url('admin/users'), $count.' deleted record(s)'
        );
    }

    public function validateID()
    {
        $this->isLoggedIn();

        $id = (int) $this->app->get('object_id', '');

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
