<?php

namespace app\swagger\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\input\Option;
use think\facade\Env;
use think\facade\Config;

class Swagger extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('swagger')
            ->addOption('readable', 'r', Option::VALUE_NONE, '生成人类可读的JSON格式文档')
        	->setDescription('生成OpenAPI文档（JSON）');
    }

    protected function execute(Input $input, Output $output)
    {
        // 获取参数
        $isReadable = $input->hasOption('readable');

    	// 扫描生成文档
        $output->writeln('扫描中');
        $openApi = \OpenApi\scan([
            Env::get('app_path')
        ]);

        // 渲染数据
        $output->writeln('渲染中' . ($isReadable ? '（可读格式）' : ''));
        if ($isReadable) {
            $data = $openApi->toJson();
        } else {
            $data = json_encode($openApi);
        }

        // 输出文件
        $output->writeln('保存中');
        $path = Config::get('swagger.swagger_ui_path') . 'api.json';
        file_put_contents($path, $data);

        $output->writeln('完毕');
    }
}
