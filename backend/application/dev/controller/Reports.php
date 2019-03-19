<?php

namespace app\dev\controller;

use think\Controller;
use think\Request;
use think\facade\Response;
use think\facade\Url;

class Reports extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('BASE', '/static/reports/');
        $this->assign('BACKEND_URL', Url::build('reports_backend_index', null, null));
        return Response::create($this->fetch(), 'html');
    }
}
