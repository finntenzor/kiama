<?php

use think\facade\Env;

return [
    // Swagger前端路径
    'swagger_ui_path'        => Env::get('root_path') . 'public/static/swagger/',
    // 应用地址
    'swagger_ui_url'         => '/static/swagger/',
];
