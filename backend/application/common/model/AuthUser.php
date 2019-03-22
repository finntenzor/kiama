<?php

namespace app\common\model;

use think\Model;
use app\common\abstracts\Auth;

/**
 * Class AuthUser
 * @package app\common\model
 * @property int id ID
 * @property string password 密码
 * @property float last_login 最后登录
 * @property bool is_superuser 是否超级用户
 * @property string usertype 用户类型
 * @property string username 用户名
 * @property bool is_sso 是否强制单点登录
 * @property string sso_token 单点登录token
 * @property array extra 扩展信息
 */
class AuthUser extends Model implements Auth
{
    /**
     * @var string
     */
    protected $table = 'auth_user';

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
     * @var array
     */
    private $permissions = null;

    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->with(['groups', 'groups.permissions', 'directPermissions']);
        $this->hidden(['is_sso', 'sso_token', 'password']);
        $this->hidden(['pivot']);
        $this->append(['permissions', 'directPermissions', 'groups']);
    }

    /**
     * @var string $code 权限标识符
     * @return bool 返回是否拥有此权限
     */
    public function can($code)
    {
        if ($this->is_superuser) {
            return true;
        } else {
            foreach ($this->permissions as $permission) {
                if ($permission->code === $code) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * 合并计算该用户的所有权限
     */
    private function mergePermissions()
    {
        // 标记
        $idMap = [];

        // 遍历直接权限
        foreach ($this->directPermissions as $permission) {
            $id = $permission->id;
            if (isset($idMap[$id])) {
                continue;
            }
            $idMap[$id] = true;
            $this->permissions[] = $permission;
        }

        // 遍历组权限
        foreach ($this->groups as $group) {
            foreach ($group->permissions as $permission) {
                $id = $permission->id;
                if (isset($idMap[$id])) {
                    continue;
                }
                $idMap[$id] = true;
                $this->permissions[] = $permission;
            }
        }

        // 按ID排序
        usort($this->permissions, function ($a, $b) {
            return $a->id - $b->id;
        });
    }

    /**
     * 返回此用户实际上的所有权限，超级用户除外
     * @return array
     */
    public function getPermissionsAttr()
    {
        if ($this->permissions === null) {
            $this->mergePermissions();
        }
        return $this->permissions;
    }

    /**
     * 此用户隶属的所有用户组
     */
    public function groups()
    {
        return $this->belongsToMany('AuthGroup', 'AuthUserGroups', 'group_id', 'user_id');
    }

    /**
     * 此用户组下所有的权限
     */
    public function directPermissions()
    {
        return $this->belongsToMany('AuthPermission', 'AuthUserPermissions', 'permission_id', 'user_id');
    }

    /**
     * @param string $value
     * @return array
     */
    public function getIsSuperuserAttr($value)
    {
        return boolval($value);
    }

    /**
     * @param array $value
     * @return string
     */
    public function setIsSuperuserAttr($value)
    {
        return $value ? 1 : 0;
    }

    /**
     * @param string $value
     * @return array
     */
    public function getIsSsoAttr($value)
    {
        return boolval($value);
    }

    /**
     * @param array $value
     * @return string
     */
    public function setIsSsoAttr($value)
    {
        return $value ? 1 : 0;
    }
}
