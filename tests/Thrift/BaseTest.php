<?php
// +----------------------------------------------------------------------
// | BaseTest.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace Tests\Thrift;

use App\Common\Zipkin\ZipkinClient;
use App\Core\Zipkin\Tracer;
use Tests\UnitTestCase;

/**
 * Class UnitTest
 */
abstract class BaseTest extends UnitTestCase
{
    /** @var  \Zipkin\Tracer */
    public $tracer;

    /** @var  Span */
    public $newTracer;

    public function begin($name)
    {
        $name = get_called_class() . '@' . $name;
        /** @var \Zipkin\Tracer $tracing */
        $tracer = di('tracer');
        $this->tracer = $tracer;
        list($new_tracer, $options) = Tracer::getInstance()->newTrace($tracer, $name);
        $this->newTracer = $new_tracer;
        ZipkinClient::getInstance()->setOptions($options);
    }

    protected function end()
    {
        ZipkinClient::getInstance()->flushInstance();
        $this->newTracer->finish();
        $this->tracer->flush();
    }

}