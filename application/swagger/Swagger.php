<?php

namespace app\swagger;

use think\facade\Route;

/**
 * Swagger
 * 外部访问类、配置类
 */
class Swagger
{
    // 命名路由前缀
    const ROUTE_PREFIX = 'swagger_';

    /**
     * 创建SwaggerApi路由组
     * @param string $groupName 组名
     * @return \think\route\RuleGroup 生成的路由组
     */
    public static function route($groupName)
    {
        return Route::group($groupName, function () {
            // swagger前端页面
            Route::get('/$', '\app\swagger\ControllerSwagger@ui')
                ->name(self::ROUTE_PREFIX . 'ui');
            // 获取api描述json
            Route::get('/api$', '\app\swagger\ControllerSwagger@api')
                ->name(self::ROUTE_PREFIX . 'api');
        });
    }
}
