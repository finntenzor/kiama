<?php
namespace app\dev\exception;

use app\common\model\BackendReport;
use app\common\util\Time;
use think\exception\Handle as ThinkHandle;
use Exception;
use think\facade\Response;
use think\facade\Request;
use think\facade\App;

class Handle extends ThinkHandle
{
    /**
     * 要忽略的错误类型
     * @var array
     */
    protected static $ignores = [];

    /**
     * 错误报告id
     * @var int
     */
    protected $reportId;

    /**
     * 忽略某个错误类型
     * @param string $classPath 类名/路径
     */
    public static function ignore($classPath)
    {
        static::$ignores[] = $classPath;
    }

    /**
     * 接管后的错误报告，将原错误渲染并保存。
     *
     * @access public
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        $this->ignoreReport = array_merge($this->ignoreReport, static::$ignores);
        // 忽略一些异常
        if ($this->isIgnoreReport($exception)) {
            return;
        }

        // 保存错误报告
        $details = $this->getDetails($exception);
        $report = new BackendReport();
        $report->title = $details['message'];
        $report->method = Request::method();
        $report->url = Request::url(true);
        $report->auth = [];
        $report->details = $details;
        $report->report_at = Time::now();
        $report->save();

        $this->reportId = $report->id;
    }

    /**
     * 返回给用户的错误报告，由ResponseBuilder决定
     *
     * @access public
     * @param  \Exception $exception
     * @return \think\Response
     */
    public function render(\Exception $exception)
    {
        return Response::create([
            'id' => $this->reportId,
            'message' => '发生错误，编号：' . $this->reportId
        ], 'json', 500);
    }

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
