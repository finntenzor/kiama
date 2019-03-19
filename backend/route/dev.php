<?php

use think\facade\Route;

Route::group('dev', function () {
    Route::get('/swagger$', 'dev/Swagger/index')
        ->name('swagger_ui');
    Route::get('/swagger/api$', 'dev/Swagger/api')
        ->name('swagger_api');

    // 显示前端页面
    Route::get('/reports$', 'dev/Reports/index')
        ->name('reports_ui');
    // 后端错误报告
    Route::get('/reports/be$', 'dev/reports.Backend/index')
        ->name('reports_backend_index');
    Route::get('/reports/be/:id', 'dev/reports.Backend/read')
        ->pattern(['id' => '\d+'])
        ->name('reports_backend_read');
    Route::delete('/reports/be/:id', 'dev/reports.Backend/delete')
        ->pattern(['id' => '\d+'])
        ->name('reports_backend_delete');
});
