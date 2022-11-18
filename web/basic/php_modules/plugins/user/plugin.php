<?php
/**
 * SPT software - Core plugin
 * 
 * @project: https://github.com/smpleader/spt-boilerplate
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 * 
 */

namespace App\plugins\user;

use SPT\App\Instance as AppIns;
use SPT\Support\Loader;
use Joomla\DI\Container;
use SPT\Plugin\CMS as PluginAbstract;
use SPT\File;
use SPT\Middleware\Dispatcher as MW;
use App\plugins\user\middlewares\Permission;
use App\plugins\user\middlewares\Validation;
use SPT\Dispatcher;

class plugin extends PluginAbstract
{ 
    public function register()
    {
        $permissionList = new Permission('plugins\users\middlewares\permission');
        MW::register('permission', $permissionList);

        $Validation = new Validation('plugins\users\middlewares\validation');
        MW::register('validation', $Validation);

        Dispatcher::register('afterNewUser', 'UserGroupModel', 'addUserMap');
        Dispatcher::register('afterNewUser', 'SitemapModel', 'createEndpoint');
        Dispatcher::register('afterNewGroup', 'SitemapModel', 'createEndpoint');
        Dispatcher::register('afterUpdateGroup', 'SitemapModel', 'updateEndpoint');
        Dispatcher::register('afterUpdateUser', 'SitemapModel', 'updateEndpoint');
        Dispatcher::register('afterUpdateUser', 'UserGroupModel', 'updateUserMap');
        Dispatcher::register('afterRemoveUser', 'UserGroupModel', 'removeByUser');
        Dispatcher::register('afterRemoveGroup', 'UserGroupModel', 'removeByGroup');

        return [
            'models' => [
                'alias' => [
                    'App\plugins\user\models\UserModel' => 'UserModel',
                    'App\plugins\user\models\GroupModel' => 'GroupModel',
                    'App\plugins\user\models\UserGroupModel' => 'UserGroupModel',
                ]
            ],
            'viewmodels' => [
                'alias' => [
                    'App\plugins\user\viewmodels\UsersVM' => 'UsersVM',
                    'App\plugins\user\viewmodels\UserVM' => 'UserVM',
                    'App\plugins\user\viewmodels\GroupsVM' => 'GroupsVM',
                    'App\plugins\user\viewmodels\GroupVM' => 'GroupVM',
                    'App\plugins\user\viewmodels\UsersPaginationVM' => 'UsersPaginationVM',
                ],
            ],
            'entity' => [],
            'file' => []
        ];
    }

    public function info()
    {
        return [
            'author' => 'Pham Minh',
            'version' =>  '0.1',
            'description' => 'User Plugin',
        ];
    }

    public function getRightAccess()
    {
        return ['user_manager', 'usergroup_manager'];
    }

    public function loadFile(Container $container)
    {
        $container->set('file', new File());
    }
    
    public function loadEntity(Container $container)
    {
        $path = AppIns::path('plugin'). 'user/entities';
        $namespace = 'App\plugins\user\entities';
        $inners = Loader::findClass($path, $namespace);
        foreach($inners as $class)
        {
            if(class_exists($class))
            {
                $entity = new $class($container->get('query'));
                $container->share( $class, $entity, true);
                $entity->checkAvailability();
                $alias = explode('\\', $class);
                $container->alias( $alias[count($alias) - 1], $class);
            }
            // else { debug this }
        }
    }

    public function registerRouter()
    {
        return [
            'admin' => [
                'login' => [
                    'title' => 'Login',
                    'fnc' => [
                        'get' => 'users.user.gate',
                        'post' => 'users.user.login',
                    ],
                    'page' => 'login',
                    'object' => '',
                ],
                'logout' => [
                    'title' => 'Logout',
                    'fnc' => 'users.user.logout',
                    'object' => '',
                    'page' => 'logout',
                ],
                'user' => [
                    'title' => 'User form create',
                    'fnc' => [
                        'get' => 'users.user.detail',
                    ],
                    'page' => 'user form create',
                    'object' => 'user',
                    'permission' => [
                        'PermissionKey' => 'user_manager',
                    ],
                ],
                'user/0' => [
                    'title' => 'Save new user',
                    'fnc' => [
                        'post' => 'users.user.add',
                    ],
                    'page' => 'user save new',
                    'object' => 'user',
                    'permission' => [
                        'PermissionKey' => 'user_manager',
                    ],
                ],
                'users'=>[
                    'title' => 'User Manager',
                    'fnc' => [
                        'get' => 'users.user.list',
                        'post' => 'users.user.list',
                        'put' => 'users.user.update', 
                        'delete' => 'users.user.delete'
                    ],
                    'page' => 'user listing',
                    'object' => 'user',
                    'permission' => [
                        'PermissionKey' => 'user_manager',
                    ],
                ],
                'user-group' => [
                    'title' => 'User Group Form',
                    'fnc' => [
                        'get' => 'users.userGroup.detail',
                        // 'post' => 'users.userGroup.add',
                        // 'put' => 'users.userGroup.update',
                        // 'delete' => 'users.userGroup.delete'
                    ],
                    'page' => 'user group form',
                    'object' => 'usergroup',
                    'permission' => [
                        'PermissionKey' => 'usergroup_manager',
                    ],
                ],
                'user-group/0' => [
                    'title' => 'Save new user group',
                    'fnc' => [
                        'post' => 'users.userGroup.add',
                    ],
                    'page' => 'user group save new',
                    'object' => 'usergroup',
                    'permission' => [
                        'PermissionKey' => 'usergroup_manager',
                    ],
                ],
                'user-groups'=>[
                    'title' => 'User Group Manager',
                    'fnc' => [
                        'get' => 'users.userGroup.list',
                        'post' => 'users.userGroup.list',
                        'put' => 'users.userGroup.update', 
                        'delete' => 'users.userGroup.delete'
                    ],
                    'page' => 'user group listing',
                    'object' => 'usergroup',
                    'permission' => [
                        'PermissionKey' => 'usergroup_manager',
                    ],
                ],
            ],
        ];
    }

    public function registerObject()
    {
        return [
            'user' => [
                'restApi' => [
                    'admin/user/',
                    'id', 
                    [
                        'put' => 'users.user.update',
                        'delete' => 'users.user.delete',
                    ],
                    '*',
                    [
                        'PermissionKey' => 'user_manager',
                    ],
                ],
                'user detail' => [
                    'admin/user/',
                    'id', 
                    'users.user.detail',
                    'get',
                    [
                        'PermissionKey' => 'user_manager',
                    ],
                ]
            ],
            'group' => [
                'restApi' => [
                    'admin/user-group/',
                    'id', 
                    [
                        'put' => 'users.userGroup.update',
                        'delete' => 'users.userGroup.delete',
                    ],
                    '*',
                    [
                        'PermissionKey' => 'usergroup_manager',
                    ],
                ],
                'user group detail' => [
                    'admin/user-group/',
                    'id', 
                    'users.userGroup.detail',
                    'get',
                    [
                        'PermissionKey' => 'usergroup_manager',
                    ],
                ]
            ],
        ];
    }


}
