<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt-boilerplate
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */
namespace App\plugins\sitemap\models;
use SPT\JDIContainer\Base;

class SitemapModel extends Base
{
    public function getSlugFromField($field, $method ,$config = '', $forceUnique = false, $oid = '')
    {
        $slug = trim(mb_strtolower($field));
        $slug = str_replace(['à','á', 'ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ'], 'a', $slug);
        $slug = str_replace(['è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ'], 'e', $slug);
        $slug = str_replace(['ì','í','ị','ỉ','ĩ'], 'i', $slug);
        $slug = str_replace(['ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ'], 'o', $slug);
        $slug = str_replace(['ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ'], 'u', $slug);
        $slug = str_replace(['ỳ','ý','ỵ','ỷ','ỹ'], 'y', $slug);
        $slug = str_replace('đ', 'd', $slug);
        $slug = preg_replace('/[^a-z0-9-\s]/', '', $slug);
        $slug = preg_replace('/([\s]+)/', '-', $slug);

        if( $forceUnique )
        {
            $where = ['slug' => $config. $slug, 'method' => $method];
            if ($oid)
            {
                $where[] = "id NOT LIKE '". $oid . "'"; 
            }

            $check = $this->SitemapEntity->findOne($where, '*');
            $i=0;
            while($check)
            {
                $i++;
                $where['slug'] = $slug. '-'. $i;
                $check = $this->SitemapEntity->findOne($where, '*');
            }

            $slug = $i ? $slug . '-'. $i : $slug;
        }
        
        return $slug;
    }

    public function createEndpoint($object, $data, $newId)
    {
        $object = $this->getObjectName($object);
        $plugin  = $this->plugin->{$this->app->get('plugin', '')};
        $config = $plugin->registerObject()[$object];
        if (!$config)
        {
            return false;
        }

        $settings = isset($data['settings']) ? json_encode($data['settings']) : json_encode([]);

        $endpoint = [
            'plugin' => $plugin->getInfo()['name'],
            'title' => ucfirst($data['title']),
            'settings' => $settings,
            'published' => 1,
            'object' => $object,
            'object_id' => $newId,
        ];

        foreach ($config as $key => $value)
        {
            $tmp = $endpoint;
            $page = $key;
            list($slug, $nameField, $fnc, $method, $permission) = $value;
            $permission = isset($permission) ? json_encode($permission) : json_encode([]);
            $method = $method ? $method : 'get';
            $field = $nameField == 'id' ? $newId : $data[$nameField];

            $tmp['slug'] = $slug. $this->getSlugFromField($field, $method, $slug, true);
            $tmp['method'] = $method;
            $tmp['title'] = $data['title'] ? $tmp['title']. ' '. $key . ' '. $newId : ucfirst($key). ' '. $newId;
            $tmp['fnc'] = is_array($fnc) ? json_encode($fnc) : $fnc;
            $tmp['permission'] = $permission;
            $tmp['page'] = $key;
            $this->SitemapEntity->add($tmp);
        }
    }

    public function updateEndpoint($object, $data)
    {
        $object = $this->getObjectName($object);
        $plugin  = $this->plugin->{$this->app->get('plugin', '')};
        $config = $plugin->registerObject()[$object];
        foreach($config as $key => $value)
        {
            list($slug, $nameField, $fnc, $method) = $value;
            if ($nameField !== 'id')
            {
                if (isset($data[$nameField]))
                {
                    $field = $data[$nameField];
                    $where = [
                        'plugin' => $plugin->getInfo()['name'],
                        'object' => $object,
                        'object_id' => $data['id'],
                        'page' => $key,
                    ];
                    $list = $this->SitemapEntity->list(0, 0, $where);
        
                    foreach ($list as $item)
                    {
                        $item['title'] = $data['title'] ? $data['title'] : $item['title'];
                        $item['slug'] = $slug. $this->getSlugFromField($field, $method, $slug, true, $item['id']);
                        $this->SitemapEntity->update($item);
                    }
                }
            }
        }
        
    }

    public function removeEndpoint($object, $id)
    {
        $object = $this->getObjectName($object);
        $plugin  = $this->plugin->{$this->app->get('plugin', '')};
        $list = $this->SitemapEntity->list(0, 0, ['plugin' => $plugin->getInfo()['name'], 'object' => $object, 'object_id' => $id]);

        foreach( $list as $item)
        {
            $this->SitemapEntity->remove($item['id']);
        }
    }

    public function getObjectName($object)
    {
        $name = explode('\\', $object);
        $name = str_replace('Entity', '', end($name));
        return strtolower($name);
    }
}