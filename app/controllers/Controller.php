<?php
// +----------------------------------------------------------------------
// | 控制器基类 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Controllers;

use App\Common\Zipkin\ZipkinClient;
use App\Core\Zipkin\Tracer;
use Zipkin\Span;
use Zipkin\Tracing;

abstract class Controller extends \Phalcon\Mvc\Controller
{
    /** @var  \Zipkin\Tracer */
    public $tracer;

    /** @var  Span */
    public $newTracer;

    public function initialize()
    {
    }

    public function beforeExecuteRoute()
    {
        /** @var Tracer $tracing */
        $tracer = di('tracer');
        $this->tracer = $tracer;
        $uri = $this->router->getRewriteUri();
        list($new_tracer, $options) = Tracer::getInstance()->newTrace($tracer, $uri);
        $this->newTracer = $new_tracer;
        ZipkinClient::getInstance()->setOptions($options);
    }

    public function afterExecuteRoute()
    {
        $this->newTracer->finish();
        $this->tracer->flush();
    }
}
