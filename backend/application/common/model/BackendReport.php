<?php

namespace app\common\model;

use think\Model;

/**
 * Class BackendReport
 * @package app\common\model
 * @property int id ID
 * @property string title 错误描述
 * @property string method 请求方法
 * @property string url 请求URL
 * @property array auth 身份验证
 * @property array details 报告详情
 * @property int report_at 错误报告记录时间
 */
class BackendReport extends Model
{
    /**
     * @var string
     */
    protected $table = 'app_backend_reports';

    /**
     * @var array
     */
    protected $type = [
        'report_at' => 'float'
    ];

    /**
     * @param string $value
     * @return array
     */
    public function getAuthAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * @param array $value
     * @return string
     */
    public function setAuthAttr($value)
    {
        return json_encode($value);
    }

    /**
     * @param string $value
     * @return array
     */
    public function getDetailsAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * @param array $value
     * @return string
     */
    public function setDetailsAttr($value)
    {
        return json_encode($value);
    }
}
