<?php

namespace app\dev\controller;

use think\Controller;
use think\facade\Env;
use think\facade\Config;
use think\facade\Url;
use think\Response;

class Swagger extends Controller
{
    /**
     * 显示文档页面
     *
     * @return \think\Response
     */
    public function index()
    {
        // 根据应用状态选择文档来源
        $base = Config::get('swagger.swagger_ui_url');
        if (Config::get('app_debug')) {
            $url = Url::build('swagger_api', null, null);
        } else {
            $url = $base . 'api.json';
        }
        $this->assign('url', $url);
        $this->assign('BASE', $base);
        return Response::create($this->fetch(), 'html');
    }

    /**
     * 返回Swagger文档描述JSON
     *
     * @return \think\Response
     */
    public function api()
    {
        // 根据应用状态选择文档来源
        if (Config::get('app_debug')) {
            $openApi = \OpenApi\scan(Env::get('app_path'));
            return json($openApi);
        } else {
            $path = Config::get('swagger.swagger_ui_path') . 'api.json';
            $data = file_get_contents($path);
            $response = new Response($data);
            $response->contentType('application/json');
            return $response;
        }
    }
}
