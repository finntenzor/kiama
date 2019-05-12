<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateAuthTables extends Migrator
{
    public function up()
    {
        $this->createDataTables();
        $this->createRelativeTables();
    }

    public function down()
    {
        $this->table('auth_user')->drop();
        $this->table('auth_permission')->drop();
        $this->table('auth_group')->drop();
        $this->table('auth_group_permissions')->drop();
        $this->table('auth_user_groups')->drop();
        $this->table('auth_user_permissions')->drop();
    }

    private function createDataTables()
    {
        $this->table('auth_user')
            ->addColumn('username', 'string', ['limit' => 200, 'comment' => '用户名'])
            ->addColumn('password', 'string', ['limit' => 128, 'comment' => '密码'])
            ->addColumn('register_at', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '用户注册时间'])
            ->addColumn('last_login', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '最后登录时间'])
            ->addColumn('is_superuser', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '是否是超级用户'])
            ->addColumn('is_active', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '该用户是否可用'])
            ->addColumn('is_sso', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '该用户是否强制单点登录'])
            ->addColumn('sso_token', 'string', ['limit' => 200, 'comment' => '单点登录token'])
            ->addColumn('extra', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'comment' => '扩展信息'])
            ->addIndex(['username'], ['unique' => true])
            ->create();

        $this->table('auth_permission')
            ->addColumn('code', 'string', ['limit' => 128, 'comment' => '权限标识符'])
            ->addColumn('permission_name', 'string', ['limit' => 200, 'comment' => '权限名称'])
            ->addColumn('custom_guard', 'string', ['limit' => 200, 'comment' => '自定义权限守卫'])
            ->addIndex(['code'], ['unique' => true])
            ->create();

        $this->table('auth_group')
            ->addColumn('group_name', 'string', ['limit' => 128, 'comment' => '用户组名'])
            ->create();
    }

    private function createRelativeTables()
    {
        $this->table('auth_group_permissions')
            ->addColumn('group_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'comment' => '组ID'])
            ->addColumn('permission_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'comment' => '权限ID'])
            ->addIndex(['group_id', 'permission_id'])
            ->addIndex(['permission_id'])
            ->create();

        $this->table('auth_user_groups')
            ->addColumn('user_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'comment' => '用户ID'])
            ->addColumn('group_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'comment' => '组ID'])
            ->addIndex(['user_id', 'group_id'])
            ->addIndex(['group_id'])
            ->create();

        $this->table('auth_user_permissions')
            ->addColumn('user_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'comment' => '用户ID'])
            ->addColumn('permission_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'comment' => '权限ID'])
            ->addIndex(['user_id', 'permission_id'])
            ->addIndex(['permission_id'])
            ->create();
    }
}
