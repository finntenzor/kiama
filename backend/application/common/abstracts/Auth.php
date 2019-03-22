<?php

namespace app\common\abstracts;

/**
 * Interface Auth
 * @package app\common\model
 * @property int id ID
 * @property string group_name 用户组名
 */
interface Auth
{
    /**
     * @var string $code 权限标识符
     * @return bool 返回是否拥有此权限
     */
    public function can($code);
}
