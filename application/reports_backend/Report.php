<?php

namespace app\reports_backend;

use think\facade\Route;

/**
 * Report
 * 外部访问类、配置类
 */
class Report
{
    // 命名路由前缀
    const ROUTE_PREFIX = 'reports_backend_';

    // 数据库中对应表名
    const TABLE_NAME = 'app_backend_reports';

    // 静态文件基址
    const STATIC_BASE_URL = '/static/reports';

    /**
     * 创建错误报告路由组
     * @param string $groupName 组名
     * @return \think\route\RuleGroup 生成的路由组
     */
    public static function route($groupName)
    {
        return Route::group($groupName, function () {
            // TODO 将前端代码上传至git
            // 错误报告前端页面
            Route::get('/', '\app\reports_backend\ControllerReport@ui')
                ->name(self::ROUTE_PREFIX . 'ui');
            // 列出错误报告
            Route::get('/reports/$', '\app\reports_backend\ControllerReport@index')
                ->name(self::ROUTE_PREFIX . 'index');
            // 获取错误报告
            Route::get('/reports/:id', '\app\reports_backend\ControllerReport@read')
                ->name(self::ROUTE_PREFIX . 'read');
            // 删除错误报告
            Route::delete('/reports/:id', '\app\reports_backend\ControllerReport@delete')
                ->name(self::ROUTE_PREFIX . 'delete');
        })->pattern(['id' => '\d+']);
    }
}
