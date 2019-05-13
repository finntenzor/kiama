<?php

namespace app\group_auth\model;

use think\Model;
use app\group_auth\abstracts\Auth;
use app\group_auth\traits\HasPermission;

/**
 * Class User
 * @package app\group_auth\model
 * @property int id ID
 * @property string username 用户名
 * @property string password 密码
 * @property float register_at 注册时间
 * @property float last_login 最后登录
 * @property bool is_superuser 是否超级用户
 * @property bool is_active 是否可用
 * @property bool is_sso 是否强制单点登录
 * @property string sso_token 单点登录token
 * @property array extra 扩展信息
 */
class User extends Model implements Auth
{
    use HasPermission;

    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * @var array
     */
    protected $type = [
        'last_login' => 'float'
    ];

    /**
     * @var array
     */
    protected $json = [
        'extra'
    ];

    /**
     * @var bool
     * 设置JSON数据返回数组
     */
    protected $jsonAssoc = true;

    /**
     * 额外初始化
     */
    protected function extraInitialize()
    {
        $this->hidden(['is_sso', 'sso_token', 'password']);
    }
}
