<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateBackendReportTable extends Migrator
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
        $this->table('app_backend_reports')
            ->addColumn('title', 'string', ['limit' => 300, 'comment' => '错误描述'])
            ->addColumn('method', 'string', ['limit' => 20, 'comment' => '请求方法'])
            ->addColumn('url', 'string', ['limit' => 300, 'comment' => '请求URL'])
            ->addColumn('auth', 'text', ['comment' => '身份验证'])
            ->addColumn('details', 'text', ['comment' => '报告详情'])
            ->addColumn('report_at', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '错误报告记录时间'])
            ->create();
    }

    public function down()
    {
        $this->table('app_backend_reports')->drop();
    }
}
