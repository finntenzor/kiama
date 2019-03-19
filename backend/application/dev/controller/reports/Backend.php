<?php

namespace app\dev\controller\reports;

use think\Controller;
use think\Request;
use app\common\model\BackendReport as ReportModel;
use think\Db;
use think\facade\Response;

class Backend extends Controller
{
    /**
     * @var array
     */
    protected $indexFields = [
        'id',
        'title',
        'method',
        'url',
        'auth',
        'report_at'
    ];

    /**
     * 显示错误报告列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return ReportModel::field($this->indexFields)->paginate(10);
    }

    /**
     * 显示指定的错误报告
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        return ReportModel::get($id) ?: Response::create([
            'message' => 'no such report'
        ], 'json', 404);
    }

    /**
     * 删除指定错误报告
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        ReportModel::where('id', $id)->delete();
        return;
    }
}
