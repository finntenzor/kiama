<?php

namespace app\common\util;

class Time
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
