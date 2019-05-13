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
        $this->table('auth_permission')->drop();
        $this->table('auth_group')->drop();
        $this->table('auth_group_permissions')->drop();
        $this->table('auth_user_groups')->drop();
        $this->table('auth_user_permissions')->drop();
    }

    /**
     * 创建权限数据表，包括：
     *   - 权限表
     *   - 用户组表
     */
    private function createDataTables()
    {
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

    /**
     * 创建权限关系表，包括：
     *   - 用户组权限
     *   - 用户权限
     *   - 用户-用户组
     */
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
