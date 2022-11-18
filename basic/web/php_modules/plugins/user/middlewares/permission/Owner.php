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

class Owner extends Script
{
    public function allow($params)
    {
        // nothing to do now
        if (!$this->next) {
            return false;
        }

        return $this->next->allow($params);
    }
}