<?php

namespace App\Thrift;

use App\Core\Zipkin\Tracer;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TMultiplexedProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TSocket;
use Xin\Thrift\ZipkinService\Options;
use Zipkin\Propagation\TraceContext;
use Zipkin\Tracing;

abstract class Client implements ClientInterface
{
    public static $_instance = [];

    protected $host;

    protected $port;

    protected $service;

    protected $clientName;

    protected $client;

    protected $persist = false;

    protected $debugHandler = null;

    protected $rBufSize = 512;

    protected $wBufSize = 512;

    protected $recvTimeoutMilliseconds;

    protected $sendTimeoutMilliseconds;

    private function __construct($className, $config = [])
    {
        if (isset($config['host'])) {
            $this->host = $config['host'];
        }

        if (isset($config['port'])) {
            $this->port = $config['port'];
        }

        if (!isset($this->host)) {
            throw new ClientException('Thrift Client host is required!');
        }

        if (!isset($this->port)) {
            throw new ClientException('Thrift Client port is required!');
        }

        if (!isset($this->service)) {
            throw new ClientException('Thrift Client service is required!');
        }

        if (!isset($this->clientName)) {
            throw new ClientException('Thrift Client Name is required!');
        }

        $socket = new TSocket($this->host, $this->port, $this->persist, $this->debugHandler);

        if (isset($this->recvTimeoutMilliseconds)) {
            $socket->setRecvTimeout($this->recvTimeoutMilliseconds);
        }

        if (isset($this->sendTimeoutMilliseconds)) {
            $socket->setSendTimeout($this->sendTimeoutMilliseconds);
        }

        // 创建通讯对象
        $transport = new TBufferedTransport($socket, $this->rBufSize, $this->wBufSize);

        // 创建Binary协议对象
        $protocol = new TBinaryProtocol($transport);

        // 打开通讯通道
        $transport->open();

        // 创建多元协议对象
        $protocol = new TMultiplexedProtocol($protocol, $this->service);

        $class = $this->clientName;
        $this->client = new $class($protocol);

    }

    public static function getInstance($config = [])
    {
        $class = get_called_class();
        if (isset(static::$_instance[$class]) && static::$_instance[$class] instanceof ClientInterface) {
            return static::$_instance[$class];
        }
        return static::$_instance[$class] = new static($class, $config);
    }

    public function flush()
    {
        $class = get_called_class();
        static::$_instance[$class] = null;
        return true;
    }

    public static function __callStatic($name, $arguments)
    {
        $obj = static::getInstance();
        return $obj->client->$name(...$arguments);
    }

    public function __call($name, $arguments)
    {
        $options = end($arguments);
        /** @var Tracing $tracing */
        $tracing = di('tracer');
        $tracer = $tracing->getTracer();

        if (!$options instanceof Options) {
            // 首次调用
            $d = debug_backtrace()[1];
            $spanName = $d['class'] . '@' . $d['function'];

            list($new_trace, $options) = Tracer::getInstance()->newTrace($tracer, $spanName);
            $arguments[] = $options;
        }

        $spanName = get_called_class() . '@' . $name;
        list($child_trace, $options) = Tracer::getInstance()->newChild($tracer, $spanName, $options);
        array_pop($arguments);
        $arguments[] = $options;

        try {
            $result = $this->client->$name(...$arguments);
        } finally {
            if (isset($child_trace)) {
                $child_trace->finish();
            }
            if (isset($new_trace)) {
                $new_trace->finish();
            }
            $tracer->flush();
        }

        return $result;
    }

}

