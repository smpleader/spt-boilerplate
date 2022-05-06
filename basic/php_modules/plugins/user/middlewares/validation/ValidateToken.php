<?php
/**
 * SPT software - Application Instance
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Application Instance
 * 
 */

namespace App\plugins\user\middlewares\validation;

use SPT\Middleware\Script;
use SPT\App\Instance as AppIns;

class ValidateToken extends Script
{
    public function validate($params)
    {
        $requestToken = AppIns::factory('request')->post->get('token', '', 'string');
        $try = AppIns::factory('app')->validateToken($requestToken);
        if (!$try) return false;

        if (!$this->next) {
            return true;
        }

        return $this->next->validate($params);
    }
}