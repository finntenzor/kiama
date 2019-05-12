<?php

namespace app\group_auth\model;

use think\model\Pivot;

/**
 * Class UserPermissions
 * @package app\group_auth\model
 * @property int id ID
 * @property int user_id 用户ID
 * @property int permission_id 权限ID
 */
class UserPermissions extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'auth_user_permissions';
}
