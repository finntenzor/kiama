<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateBackendReportTable extends Migrator
{
    public function up()
    {
        $this->table('app_backend_reports')
            ->addColumn('title', 'string', ['limit' => 300, 'comment' => '错误描述'])
            ->addColumn('method', 'string', ['limit' => 20, 'comment' => '请求方法'])
            ->addColumn('url', 'string', ['limit' => 300, 'comment' => '请求URL'])
            ->addColumn('auth', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'comment' => '身份验证'])
            ->addColumn('details', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'comment' => '报告详情'])
            ->addColumn('report_at', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '错误报告记录时间'])
            ->create();
    }

    public function down()
    {
        $this->table('app_backend_reports')->drop();
    }
}
