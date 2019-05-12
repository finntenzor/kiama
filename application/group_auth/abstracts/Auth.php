<?php

namespace app\group_auth\abstracts;

/**
 * Interface Auth
 * @package app\group_auth\abstracts
 */
interface Auth
{
    /**
     * @var string $code 权限标识符
     * @return bool 返回是否拥有此权限
     */
    public function can($code);
}
