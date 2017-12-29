<?php

namespace App\Tasks;

use App\Common\Zipkin\ZipkinClient;
use App\Core\Zipkin\Tracer;
use Phalcon\Cli\Dispatcher;
use Zipkin\Span;
use Zipkin\Tracing;

/**
 * Class Task
 * @package App\Tasks
 * @property Dispatcher $dispatcher
 */
abstract class Task extends \Phalcon\Cli\Task
{
    public $description;

    /** @var  \Zipkin\Tracer */
    public $tracer;

    /** @var  Span */
    public $newTracer;

    public function onConstruct()
    {
        /**
         * Phalcon\Cli\Task constructor
         *
         * public final function __construct()
         * {
         *     if method_exists(this, "onConstruct") {
         *         this->{"onConstruct"}();
         *     }
         * }
         */
    }

    public function beforeExecuteRoute()
    {
        /** @var Tracer $tracing */
        $tracer = di('tracer');
        $this->tracer = $tracer;
        $task = $this->dispatcher->getTaskName();
        $action = $this->dispatcher->getActionName();
        $name = $task . '@' . $action;
        list($new_tracer, $options) = Tracer::getInstance()->newTrace($tracer, $name);
        $this->newTracer = $new_tracer;
        ZipkinClient::getInstance()->setOptions($options);
    }

    public function afterExecuteRoute()
    {
        $this->newTracer->finish();
        $this->tracer->flush();
    }
}

