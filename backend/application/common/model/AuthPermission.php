<?php

namespace app\common\model;

use think\Model;

/**
 * Class AuthPermission
 * @package app\common\model
 * @property int id ID
 * @property string code 权限描述符
 * @property string permission_name 权限名称
 */
class AuthPermission extends Model
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
