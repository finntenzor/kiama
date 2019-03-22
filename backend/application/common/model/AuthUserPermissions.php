<?php

namespace app\common\model;

use think\model\Pivot;

/**
 * Class AuthUserPermissions
 * @package app\common\model
 * @property int id ID
 * @property int user_id 用户ID
 * @property int permission_id 权限ID
 */
class AuthUserPermissions extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'auth_user_permissions';
}
