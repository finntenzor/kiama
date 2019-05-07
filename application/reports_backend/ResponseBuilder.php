<?php

namespace app\reports_backend;

use think\facade\Response;

/**
 * ResponseBuilder
 *
 * 用于控制响应如何产生，与前端耦合
 */
class ResponseBuilder
{
    /**
     * 返回成功的响应
     * @param mixed $data
     *
     * @return \think\response\Json
     */
    public static function success($data)
    {
        return Response::create($data, 'json', 200);
    }

    /**
     * 返回失败的响应
     * @param mixed $data
     *
     * @return \think\response\Json
     */
    public static function error($message, $status = 500,  $extra = [])
    {
        $data = [
            'message' => $message
        ];
        $data = array_merge($data, $extra);
        return Response::create($data, 'json', $status);
    }

    /**
     * 返回视图响应
     * @param string    $template 模板文件
     * @param array     $vars 模板变量
     * @param integer   $code 状态码
     *
     * @return \think\response\View
     */
    public static function view($template, $vars = [], $code = 200)
    {
        return Response::create($template, 'view', $code)->assign($vars);
    }
}
