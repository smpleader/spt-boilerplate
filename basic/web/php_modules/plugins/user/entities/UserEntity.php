<?php
/**
 * SPT software - PHP Session
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: PHP Session
 * 
 */

namespace App\plugins\user\entities;

use SPT\Storage\DB\DipatchableEntity as ParentEntity;

class UserEntity extends ParentEntity
{
    protected $affix = 'User';
    protected $table = '#__users';
    protected $pk = 'id';

    public function getFields()
    {
        return [
                'id' => [
                    'type' => 'int',
                    'pk' => 1,
                    'option' => 'unsigned',
                    'extra' => 'auto_increment',
                ],
                'name' => [
                    'type' => 'varchar',
                    'limit' => 100,
                ],
                'username' => [
                    'type' => 'varchar',
                    'limit' => 100,
                ],
                'password' => [
                    // 'validate' => ['md5'],
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'email' => [
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'status' => [
                    'type' => 'tinyint',
                ],
                'avatar' => [
                    'type' => 'text'
                ],
                'created_at' => [
                    'type' => 'datetime',
                    'default_value' => '0000-00-00 00:00:00',
                ],
                'created_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'modified_at' => [
                    'type' => 'datetime',
                    'default_value' => '0000-00-00 00:00:00',
                ],
                'modified_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
        ];
    }

    public function togglePublishment( $id, $action)
    {
        $item = $this->findByPK($id);
        $status = $action == 'active' ? 1 : 0;
        return $this->db->table( $this->table )->update([
            'status' => $status,
        ], ['id' => $id ]);
    }

    public function getGroups($user_id)
    {
        $list = $this->db->select( 'usermap.user_id, usergroup.name as group_name, usergroup.id as group_id' )
                        ->table( '#__user_usergroup_map as usermap' )
                        ->join( 'LEFT JOIN #__user_groups as usergroup ON usergroup.id = usermap.group_id ')
                        ->where(['usermap.user_id = ' .$user_id]);

        return $list->list(0, 0);
    }
}