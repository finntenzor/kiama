<?php

namespace app\common\model;

use think\model\Pivot;

/**
 * Class AuthUserGroups
 * @package app\common\model
 * @property int id ID
 * @property int user_id 用户ID
 * @property int group_id 用户组ID
 */
class AuthUserGroups extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'auth_user_groups';
}
