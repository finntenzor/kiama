<?php

namespace app\reports_backend;

class Util
{
    /**
     * 返回当前时间戳（ms为单位）
     *
     * @return float
     */
    public static function now()
    {
        return round(microtime(true) * 1000);
    }
}
