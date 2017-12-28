<?php
// +----------------------------------------------------------------------
// | AsyncHttpReporter.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Common\Zipkin;

use App\Jobs\ZipkinJob;
use App\Utils\Queue;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use Xin\Traits\Common\InstanceTrait;
use Zipkin\Recording\Span;
use Zipkin\Reporter;

class AsyncHttpReporter implements Reporter
{

    const DEFAULT_OPTIONS = [
        'baseUrl' => 'http://localhost:9411',
        'endpoint' => '/api/v2/spans',
    ];

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $options;

    public function __construct(
        ClientInterface $client,
        LoggerInterface $logger,
        array $options = []
    )
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->options = array_merge(self::DEFAULT_OPTIONS, $options);
    }

    /**
     * @param Span[] $spans
     * @return void
     */
    public function report(array $spans)
    {
        Queue::push(new ZipkinJob($spans, $this->options));
    }
}