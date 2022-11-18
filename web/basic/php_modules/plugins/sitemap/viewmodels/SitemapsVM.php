<?php
/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace App\plugins\sitemap\viewmodels; 

use SPT\View\Gui\Form;
use SPT\View\Gui\Listing;
use SPT\View\VM\JDIContainer\ViewModel; 

class SitemapsVM extends ViewModel
{
    protected $alias = 'AdminSitemapsVM';
    protected $layouts = [
        'layouts.backend.sitemap' => [
            'list',
            'list.row',
            'list.filter'
        ]
    ];

    public function list()
    {
        $filter = $this->filter();

        $limit  = $filter->getField('limit')->value;
        $plugin   = $filter->getField('plugin')->value;
        $search = $filter->getField('search')->value;
        $status = $filter->getField('published')->value;
        $page   = $this->request->get->get('page', 1, 'int');
        $sort = '';

        if (($this->request->server->get('REQUEST_METHOD') == 'POST'))
        {
            $this->app->redirect($this->router->url('admin/sitemaps'));
        }

        $start  = ($page-1) * $limit;

        $where = ['method' => 'get'];
        if( !empty($search) )
        {
            $where[] = "(`title` LIKE '%".$search."%' OR `slug` LIKE '%".$search."%' OR `plugin` LIKE '%".$search."%')";
        }
        if( !empty($plugin) )
        {
            $where[] = "(`plugin` LIKE '%".$plugin."%' )";
        }
        if( !empty($status) || $status === '0' )
        {
            $where[] = "(`published` = $status )";
        }

        $result = $this->SitemapEntity->list( $start, $limit, $where, $sort);
        $total = $this->SitemapEntity->getListTotal();
        if (!$result)
        {
            $result = [];
            $total = 0;
            $this->session->set('flashMsg', 'Not found result');
        }
  
        $i = 0;
        foreach($result as $key => $value)
        {
            $result[$key]['permission'] = (array) json_decode($value['permission']);
        }

        $list   = new Listing($result, $total, $limit, $this->getColumns() );

        $this->set('sort', $sort, true);
        $this->set('start', $start, true);
        $this->set('list', $list, true);
        $this->set('page', $page, true);
        $this->set('total', $total, true);
        $this->set('url', $this->router->url(), true); 
        $this->set('link_sitemap_list', $this->router->url('admin/sitemaps'), true); 
        $this->set('token', $this->app->getToken(), true);
    }

    public function getColumns()
    {
        return [
            'num' => '#',
            'title' => 'Title',
            'plugin' => 'Plugin',
            'slug' => 'Slug',
            'permission' => 'Permission',
            'oid' => 'Object ID',
            'status' => 'Status',
            'lang' => 'Lang',
            'col_last' => ' ',
        ];
    }

    protected $_filter;
    public function filter()
    {
        if( null === $this->_filter):
            $data = [
                'search' => $this->state('search', '', '', 'post', 'sitemap.search'),
                'published' => $this->state('published', '','', 'post', 'sitemap.published'),
                'limit' => $this->state('limit', 10, 'int', 'post', 'sitemap.limit'),
                'plugin' => $this->state('plugin', '', '', 'post', 'sitemap.plugin')
            ];

            $filter = new Form($this->getFilterFields(), $data);
            $this->set('form', ['filter' => $filter], true);
            foreach($data as $k=>$v) $this->set($k, $v);
            $this->_filter = $filter;
        endif;
         
        return $this->_filter;
    }

    public function getFilterFields()
    {
        $plugins[] = ['text' => 'All Plugin', 'value' => ''];
        foreach($this->config->plugins as $item )
        {
            $plugins[] = [
                'text' => $item,
                'value' => $item,
            ];
        }

        return [
            'search' => ['text',
                'default' => '',
                'showLabel' => false,
                'formClass' => 'form-control h-full input_common w_full_475',
                'placeholder' => 'Search..'
            ],
            'published' => ['option',
                'options' => [
                    ['text' => 'All Status', 'value' => ''],
                    ['text' => 'Active', 'value' => '1'],
                    ['text' => 'Block', 'value' => '0'],
                ],
                'type' => 'select2',
                'showLabel' => false,
                'formClass' => 'w-full input_common',
            ],
            'limit' => ['option',
                'options' => [
                    ['text' => '10', 'value' => 10],
                    ['text' => '20', 'value' => 20],
                    ['text' => '50', 'value' => 50],
                ],
                'type' => 'select2',
                'showLabel' => false,
                'formClass' => 'w-full input_common',
            ],
            'plugin' => ['option',
                'options' => $plugins,
                'default' => 'All Plugin',
                'type' => 'select2',
                'showLabel' => false,
                'formClass' => 'w-full input_common',
            ],
            'token' => ['hidden',
                'default' => $this->app->getToken(),
            ],
        ];
    }

    public function row()
    {
        $this->set('item', $this->view->list->getRow());
        $this->set('index', $this->view->list->getIndex());
    }
}