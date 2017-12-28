<?php
// +----------------------------------------------------------------------
// | Tracer.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Core\Zipkin;

use Xin\Thrift\ZipkinService\Options;
use Xin\Traits\Common\InstanceTrait;
use Zipkin\Propagation\TraceContext;
use Zipkin\Tracer as ZipkinTracer;

class Tracer
{
    use InstanceTrait;

    public function newTrace(ZipkinTracer $tracer, $spanName)
    {
        $trace = $tracer->newTrace();
        $trace->setName($spanName);
        $trace->start();

        $context = $trace->getContext();
        $options = new Options();
        $options->traceId = $context->getTraceId();
        $options->parentSpanId = $context->getParentId();
        $options->spanId = $context->getSpanId();
        $options->sampled = $context->isSampled();

        return [$trace, $options];
    }

    public function newChild(ZipkinTracer $tracer, $spanName, Options $options)
    {
        $context = TraceContext::create(
            $options->traceId,
            $options->spanId,
            $options->parentSpanId,
            $options->sampled
        );
        $trace = $tracer->newChild($context);
        $trace->setName($spanName);
        $trace->start();
        $context = $trace->getContext();
        $options = new Options();
        $options->traceId = $context->getTraceId();
        $options->parentSpanId = $context->getParentId();
        $options->spanId = $context->getSpanId();
        $options->sampled = $context->isSampled();
        return [$trace, $options];
    }
}