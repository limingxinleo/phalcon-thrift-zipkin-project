<?php
// +----------------------------------------------------------------------
// | 控制器基类 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Controllers;

use Zipkin\Tracing;

abstract class Controller extends \Phalcon\Mvc\Controller
{
    public $tracer;

    public function initialize()
    {
    }

    public function beforeExecuteRoute()
    {
        /** @var Tracing $tracing */
        $tracing = di('tracer');
        $tracer = $tracing->getTracer();
    }

    public function afterExecuteRoute()
    {
        // 在每一个找到的动作后执行
    }
}
