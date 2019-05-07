<?php

namespace app\reports_backend\exception;

use Exception;
use think\facade\App;

trait ExceptionParser
{
    /**
     * 收集错误数据
     * @param Exception $exception
     * @return array
     */
    protected function getDetails(Exception $exception)
    {
        // 获取echo
        while (ob_get_level() > 1) {
            ob_end_clean();
        }
        $echo = ob_get_clean();

        // 获取常量
        $const = get_defined_constants(true);
        $const = isset($const['user']) ? $const['user'] : [];

        // 收集异常数据
        $detials = [
            'name'    => get_class($exception),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'message' => $this->getMessage($exception),
            'trace'   => $exception->getTrace(),
            'code'    => $this->getCode($exception),
            'source'  => $this->getSourceCode($exception),
            'data'    => $this->getExtendData($exception),
            'echo'    => $echo,
            'tables'  => [
                'GET Data'              => $_GET,
                'POST Data'             => $_POST,
                'Files'                 => $_FILES,
                'Cookies'               => $_COOKIE,
                'Session'               => isset($_SESSION) ? $_SESSION : [],
                'Server/Request Data'   => $_SERVER,
                'Environment Variables' => $_ENV,
                'User Constants'    => $const,
            ],
            'think_version' => App::version()
        ];

        // 格式化数据
        self::refactorTrace($detials['trace']);
        self::refactorTables($detials['tables']);
        self::refactorTables($detials['data']);

        return $detials;
    }

    /**
     * 将trace数据转为可JSON化格式
     * @param array $trace
     * @return array
     */
    public static function refactorTrace(&$trace)
    {
        foreach ($trace as &$item) {
            if (isset($item['args'])) {
                $item['args'] = self::refactorArgs($item['args']);
            }
        }
    }

    /**
     * 将args数据转为可JSON化格式
     * @param array $args
     * @return array
     */
    public static function refactorArgs($args)
    {
        $result = [];

        foreach ($args as $key => $item) {
            switch (true) {
                case is_object($item):
                    $value = [
                        'type' => 'object',
                        'value' => get_class($item)
                    ];
                    break;
                case is_array($item):
                    $value = [
                        'type' => 'array',
                        'value' => self::refactorArgs($item)
                    ];
                    break;
                case is_string($item):
                    $value = [
                        'type' => 'string',
                        'value' => $item
                    ];
                    break;
                case is_int($item):
                case is_float($item):
                    $value = [
                        'type' => 'number',
                        'value' => $item
                    ];
                    break;
                case is_null($item):
                    $value = [
                        'type' => 'null'
                    ];
                    break;
                case is_bool($item):
                    $value = [
                        'type' => 'boolean',
                        'value' => !!$item
                    ];
                    break;
                case is_resource($item):
                    $value = [
                        'type' => 'resource'
                    ];
                    break;
                default:
                    $value = [
                        'type' => 'raw',
                        'value' => str_replace("\n", '', var_export(strval($item), true))
                    ];
                    break;
            }

            if (is_int($key)) {
                $result[] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * 将table数据转为可JSON化格式
     * @param array $table
     * @return array
     */
    public static function refactorTables(&$tables)
    {
        foreach ($tables as $caption => &$table) {
            foreach ($table as $key => $value) {
                if (is_array($value) || is_object($value)){
                    $table[$key] = json_decode(json_encode($value), true);
                } else if(is_bool($value)) {
                    $table[$key] = !!$value;
                } else if(is_scalar($value)) {
                    $table[$key] = $value;
                } else {
                    $table[$key] = 'Resource';
                }
            }
        }
    }
}
