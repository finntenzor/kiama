<?php

namespace app\group_auth\model;

use think\Model;

/**
 * Class Group
 * @package app\group_auth\model
 * @property int id ID
 * @property string group_name 用户组名
 */
class Group extends Model
{
    /**
     * @var string
     */
    protected $table = 'auth_group';

    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->hidden(['pivot']);
    }

    /**
     * 此用户组下所有的权限
     */
    public function permissions()
    {
        return $this->belongsToMany('Permission', 'GroupPermissions', 'permission_id', 'group_id');
    }
}
