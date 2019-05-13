<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUserables extends Migrator
{
    public function up()
    {
        $this->table('user')
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
    }

    public function down()
    {
        $this->table('user')->drop();
    }
}
