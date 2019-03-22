<?php

namespace app\common\model;

use think\model\Pivot;

/**
 * Class AuthGroupPermissions
 * @package app\common\model
 * @property int id ID
 * @property int group_id 用户组ID
 * @property int permission_id 权限ID
 */
class AuthGroupPermissions extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'auth_group_permissions';
}
