<?php

namespace App\Jobs;

use App\Core\Logger;
use App\Jobs\Contract\JobInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Zipkin\Recording\Span;

class ZipkinJob implements JobInterface
{
    public $spans;

    public $options;

    public function __construct(array $spans, array $options)
    {
        $this->spans = $spans;

        $this->options = $options;
    }

    public function handle()
    {
        $spans = $this->spans;
        $body = json_encode(array_map(function (Span $span) {
            return $span->toArray();
        }, $spans));

        $client = new Client();
        $logger = new Logger();

        try {
            $client->request(
                'POST',
                $this->options['baseUrl'] . $this->options['endpoint'],
                ['body' => $body]
            );
        } catch (GuzzleException $e) {
            $logger->error(sprintf('traces were lost: %s', $e->getMessage()));
        }
    }

}

