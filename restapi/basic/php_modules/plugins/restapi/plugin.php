<?php
/**
 * SPT software - Stater plugin
 * 
 * @project: https://github.com/smpleader/spt-boilerplate
 * @author: Pham Minh - smpleader
 * @description: Just a basic plugin
 * 
 */

namespace App\plugins\restapi;

use SPT\Plugin\CMS as PluginAbstract;

class plugin extends PluginAbstract
{ 
    public function register()
    {
        return [
            // write your code here
        ];
    }

    public function getInfo()
    {
        return [
            'name' => 'restapi',
            'author' => 'Pham Minh',
            'version' =>  '0.1',
            'description' => 'Sample Plugin'
        ];
    }

}
