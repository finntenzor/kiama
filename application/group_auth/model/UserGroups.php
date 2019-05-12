<?php

namespace app\group_auth\model;

use think\model\Pivot;

/**
 * Class UserGroups
 * @package app\group_auth\model
 * @property int id ID
 * @property int user_id 用户ID
 * @property int group_id 用户组ID
 */
class UserGroups extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'auth_user_groups';
}
