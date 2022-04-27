<?php
/**
 * SPT software - Application
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic Application implement mvc
 * 
 */

namespace App\libraries; 

use SPT\App\JDIContainer\CMSApp; 

class appPlg extends CMSApp
{
    public function getName(string $extra='')
    {
        return 'App\\'. $extra;
    }

    public function redirect($url = null, $msg = null)
    {
        if( !empty($msg) )
        {
            $this->session->set('flashMsg', $msg);
        }

        parent::redirect($url);
    }

    public function prepareUser()
    {
        
    }
}
