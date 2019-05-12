<?php

namespace app\group_auth\model;

use think\model\Pivot;

/**
 * Class GroupPermissions
 * @package app\group_auth\model
 * @property int id ID
 * @property int group_id 用户组ID
 * @property int permission_id 权限ID
 */
class GroupPermissions extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'auth_group_permissions';
}
