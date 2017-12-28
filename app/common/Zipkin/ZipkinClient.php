<?php
// +----------------------------------------------------------------------
// | Client.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Common\Zipkin;

use Xin\Thrift\ZipkinService\Options;
use Xin\Traits\Common\InstanceTrait;
use Exception;
use Zipkin\Propagation\TraceContext;

class ZipkinClient
{
    use InstanceTrait;

    public $context;

    public $options;

    public function __construct()
    {
        if (defined('IS_MEMORY_RESIDENT') && IS_MEMORY_RESIDENT === true) {
            throw new Exception('CLI模式下，不允许使用单例对象作为调用链存储方式');
        }
    }

    public function setOptions(Options $options)
    {
        $context = TraceContext::create(
            $options->traceId,
            $options->spanId,
            $options->parentSpanId,
            $options->sampled
        );

        $this->context = $context;
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getContext()
    {
        return $this->context;
    }
}