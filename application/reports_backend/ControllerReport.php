<?php

namespace app\reports_backend;

use think\Controller;
use app\reports_backend\model\ModelReport;

class ControllerReport extends Controller
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
     * @return \think\response\View
     */
    public function ui()
    {
        $STATIC_URL = Report::STATIC_BASE_URL;
        $API_URL = Url::build(Report::ROUTE_PREFIX . 'index', null, null);
        return ResponseBuilder::view(__DIR__ . '/view/index.html', \compact(['STATIC_URL', 'API_URL']));
    }

    /**
     * 显示错误报告列表
     *
     * @return \think\response\Json
     */
    public function index()
    {
        $data = ModelReport::field($this->indexFields)->paginate(10);
        return ResponseBuilder::success($data);
    }

    /**
     * 显示指定的错误报告
     *
     * @param  int  $id
     * @return \think\response\Json
     */
    public function read($id)
    {
        $data = ModelReport::get($id);
        if ($data) {
            return ResponseBuilder::success($data);
        } else {
            return ResponseBuilder::error('no such report', 404);
        }
    }

    /**
     * 删除指定错误报告
     *
     * @param  int  $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        $count = ModelReport::where('id', $id)->delete();
        if ($count > 0) {
            return ResponseBuilder::success(null);
        } else {
            return ResponseBuilder::error('no such report', 404);
        }
    }
}
