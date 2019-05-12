<?php

namespace app\group_auth\model;

use think\Model;

/**
 * Class Permission
 * @package app\group_auth\model
 * @property int id ID
 * @property string code 权限描述符
 * @property string permission_name 权限名称
 */
class Permission extends Model
{
    /**
     * @var string
     */
    protected $table = 'auth_permission';

    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->hidden(['pivot']);
    }
}
