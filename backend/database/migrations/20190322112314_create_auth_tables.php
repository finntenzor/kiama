<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;
use think\facade\Env;

class CreateAuthTables extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->createDataTables();
        $this->createRelativeTables();
        $this->createRootUser();
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
            ->addColumn('password', 'string', ['limit' => 128, 'comment' => '密码'])
            ->addColumn('last_login', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '最后登录时间'])
            ->addColumn('is_superuser', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '是否是超级用户'])
            ->addColumn('usertype', 'string', ['limit' => 128, 'comment' => '用户类型'])
            ->addColumn('username', 'string', ['limit' => 200, 'comment' => '用户名'])
            ->addColumn('is_sso', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '该用户是否强制单点登录'])
            ->addColumn('sso_token', 'string', ['limit' => 200, 'comment' => '单点登录token'])
            ->addColumn('extra', 'text', ['comment' => '扩展信息'])
            ->addIndex(['usertype', 'username'], ['unique' => true])
            ->create();

        $this->table('auth_permission')
            ->addColumn('code', 'string', ['limit' => 128, 'comment' => '权限标识符'])
            ->addColumn('permission_name', 'string', ['limit' => 200, 'comment' => '权限名称'])
            ->addIndex(['code'])
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

    private function createRootUser()
    {
        $rootPassword = md5(rand() . sha1(rand() . md5(rand())));

        $rootUser = [
            'id' => 1,
            'password' => password_hash($rootPassword, PASSWORD_DEFAULT),
            'last_login' => 0,
            'is_superuser' => 1,
            'usertype' => 'Reserved',
            'username' => 'root',
            'is_sso' => 1,
            'sso_token' => '',
            'extra' => '{}',
        ];

        $table = $this->table('auth_user');
        $table->insert($rootUser);
        $table->saveData();
    }
}
