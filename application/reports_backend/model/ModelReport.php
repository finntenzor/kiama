<?php

namespace app\reports_backend\model;

use think\Model;
use app\reports_backend\Report;

/**
 * Class Report
 * @package app\reports_backend\model
 * @property int id ID
 * @property string title 错误描述
 * @property string method 请求方法
 * @property string url 请求URL
 * @property array auth 身份验证
 * @property array details 报告详情
 * @property int report_at 错误报告记录时间
 */
class ModelReport extends Model
{
    /**
     * @var string
     */
    protected $table = Report::TABLE_NAME;

    /**
     * @var array
     */
    protected $type = [
        'report_at' => 'float'
    ];

    /**
     * @var array
     */
    protected $json = [
        'auth',
        'details'
    ];

    /**
     * @var bool
     * 设置JSON数据返回数组
     */
    protected $jsonAssoc = true;
}
