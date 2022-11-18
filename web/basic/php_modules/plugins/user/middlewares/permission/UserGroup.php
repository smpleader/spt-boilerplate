<?php
/**
 * SPT software - Application Instance
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Application Instance
 * 
 */

namespace App\plugins\user\middlewares\permission;

use SPT\Middleware\Script;
use SPT\App\Instance as AppIns;

class UserGroup extends Script
{
    public function allow($params)
    {
        if ( is_array( $params['UserGroup'] ))
        {
            $user = AppIns::factory('user');
            foreach($user->getGroups() as $group)
            {
                if( in_array( $group, $params['UserGroup']) )
                {
                    return true;
                }
            }
        } 

        if (!$this->next) {
            return false;
        }

        return $this->next->allow($params);
    }
}