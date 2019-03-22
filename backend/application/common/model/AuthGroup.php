<?php

namespace app\common\model;

use think\Model;

/**
 * Class AuthGroup
 * @package app\common\model
 * @property int id ID
 * @property string group_name 用户组名
 */
class AuthGroup extends Model
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
        return $this->belongsToMany('AuthPermission', 'AuthGroupPermissions', 'permission_id', 'group_id');
    }
}
