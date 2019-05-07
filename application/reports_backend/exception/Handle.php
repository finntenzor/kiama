<?php

namespace app\reports_backend\exception;

use Exception;
use app\reports_backend\model\ModelReport;
use app\reports_backend\Util;
use think\facade\Response;
use think\facade\Request;
use think\facade\Log;
use think\exception\Handle as ThinkHandle;
use think\exception\DbException;
use think\exception\ErrorException;
use app\reports_backend\ResponseBuilder;

class Handle extends ThinkHandle
{
    use ExceptionParser;

    // 错误报告过程状态
    const REPORT_OK = 0;
    const REPORT_DB_EXCEPTION = 1;
    const REPORT_FILE_EXCEPTION = 2;
    const REPORT_OTHER_EXCEPTION = 4;

    /**
     * 要忽略的错误类型
     * @var array
     */
    protected $ignoreReport = [
        '\\think\\exception\\HttpException',
    ];

    /**
     * 错误报告id
     * @var int
     */
    protected $reportId;

    /**
     * 错误报告过程状态
     *
     * @var int
     */
    protected $reportStatus = 0;

    /**
     * 错误报告过程中剩余未处理的错误
     *
     * @var array
     */
    protected $remainedExceptions = [];

    /**
     * 接管后的错误报告，将原错误渲染并保存。
     *
     * @access public
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        // 忽略一些异常
        if ($this->isIgnoreReport($exception)) {
            return;
        }

        $exceptions = [];

        try {
            // 尝试将异常保存到数据库
            try {
                $this->reportId = $this->saveReportToDatabase($exception);
            } catch (DbException $e1) {
                $this->reportStatus |= self::REPORT_DB_EXCEPTION;
                $exceptions = [
                    [$exception, 'On Report'],
                    [$e1, 'On Report DbException']
                ];
            } catch (\Exception $e2) {
                $this->reportStatus |= self::REPORT_OTHER_EXCEPTION;
                $exceptions = [
                    [$exception, 'On Report'],
                    [$e2, 'On Report \\Exception']
                ];
            }

            // 尝试将异常保存到日志
            foreach ($exceptions as $item) {
                try {
                    $this->saveReportToLog($item[0], $item[1]);
                } catch (ErrorException $e3) {
                    $exceptions[] = [$e3, 'On Report ErrorException'];
                    if (strstr($e3->getMessage(), 'Permission') !== false) {
                        $this->reportStatus |= self::REPORT_OTHER_EXCEPTION;
                    } else {
                        $this->reportStatus |= self::REPORT_FILE_EXCEPTION;
                    }
                }
            }
        } catch (\Exception $ex) {
            $this->reportStatus |= self::REPORT_OTHER_EXCEPTION;
        } catch (\Throwable $t) {
            $this->reportStatus |= self::REPORT_OTHER_EXCEPTION;
        }

        $this->remainedExceptions = $exceptions;
    }

    /**
     * 将错误报告保存到数据库
     *
     * @access protected
     * @param  \Exception $exception
     * @throws DbException
     * @return int
     */
    protected function saveReportToDatabase(Exception $exception)
    {
        // 收集错误数据
        $details = $this->getDetails($exception);

        // 保存错误报告
        $report = new ModelReport();
        $report->title = $details['message'];
        $report->method = Request::method();
        $report->url = Request::url(true);
        $report->auth = [];
        $report->details = $details;
        $report->report_at = Util::now();
        $report->save();

        // 返回ID
        return $report->id;
    }

    /**
     * 将错误报告保存到日志
     *
     * @access protected
     * @param  \Exception $exception
     * @throws ErrorException
     * @return void
     */
    protected function saveReportToLog(Exception $exception, string $anchor = 'Anchor')
    {
        // 收集错误数据
        $details = $this->getDetails($exception);

        // 保存到日志
        $content = json_encode($details, JSON_UNESCAPED_UNICODE);
        Log::record("[Error $anchor]", 'error');
        Log::record($content, 'error');
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
        if ($this->isIgnoreReport($exception)) {
            $message = '发生错误，该错误已忽略';
        } else {
            $message = '发生错误，';

            if (($this->reportStatus & self::REPORT_DB_EXCEPTION) > 0) {
                $message .= '并且错误报告无法写入数据库，';
            }
            if (($this->reportStatus & self::REPORT_FILE_EXCEPTION) > 0) {
                $message .= '并且错误报告无法写入文件系统，';
            }
            if (($this->reportStatus & self::REPORT_OTHER_EXCEPTION) > 0) {
                $message .= '并且报告期间发生其他异常，';
            }

            if ($this->reportStatus === self::REPORT_OK) {
                $message .= '错误ID：' . $this->reportId;
            } else {
                $message .= '因无法记录，无错误ID';
            }
        }

        return ResponseBuilder::error($message, 500, [
            'report_status' => $this->reportStatus,
            'id' => $this->reportId
        ]);
    }
}
