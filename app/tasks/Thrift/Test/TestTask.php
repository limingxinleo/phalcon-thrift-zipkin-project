<?php

namespace App\Tasks\Thrift\Test;

use App\Common\Zipkin\ZipkinClient;
use App\Tasks\Task;
use App\Thrift\Clients\AppClient;
use Xin\Cli\Color;
use swoole_process;

class TestTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  Thrift 通信测试脚本') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run test:test@[action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  version                         返回版本号', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  exception                       测试异常抛出', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  client                          Client单例测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  high                            高并发测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  async                           异步测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  timeout                         延迟测试', Color::FG_GREEN) . PHP_EOL;
    }

    public function timeoutAction()
    {
        $client = AppClient::getInstance();
        dump($client->timeout());
    }

    public function exceptionAction()
    {
        $client = AppClient::getInstance();
        try {
            dump($client->testException());
        } catch (\Exception $ex) {
            dump($ex->getCode());
            dump($ex->getMessage());
        }
    }

    public function asyncAction()
    {
        $process = new swoole_process([$this, 'timeoutHandle']);
        $process->write('welcome');
        $process->start();

        $process = new swoole_process([$this, 'timeoutHandle']);
        $process->write('version');
        $process->start();

        swoole_process::wait();
    }

    public function timeoutHandle(swoole_process $worker)
    {
        swoole_event_add($worker->pipe, function ($pipe) use ($worker) {
            // 从主进程中读取到的数据
            $recv = $worker->read();
            $client = AppClient::getInstance();
            dump($client->$recv());
            $worker->exit(0);
            swoole_event_del($pipe);
        });
    }

    public function highAction($params = [])
    {
        $tasks = 10;
        if (isset($params[0]) && is_numeric($params[0])) {
            $tasks = intval($params[0]);
        }

        $time = microtime(true);
        for ($i = 0; $i < $tasks; $i++) {
            $process = new swoole_process([$this, 'highClient']);
            $pid = $process->start();
            echo Color::colorize("PID=" . $pid, Color::FG_RED) . PHP_EOL;
        }
        swoole_process::wait();
        echo Color::colorize("用时：" . (microtime(true) - $time), Color::FG_GREEN) . PHP_EOL;
    }

    public function highClient()
    {
        $client = AppClient::getInstance();
        for ($i = 0; $i < 10000; $i++) {
            $client->version();
            // echo $client->version() . PHP_EOL;
        }
    }

    /**
     * @desc   go服务调用
     * @author limx
     */
    public function versionAction()
    {
        $client = AppClient::getInstance();

        dump($client->version());
    }

    /**
     * @desc   单例测试
     * @author limx
     */
    public function clientAction()
    {
        $client = AppClient::getInstance();
        $client = AppClient::getInstance();

        echo Color::colorize($client->version(), Color::FG_GREEN) . PHP_EOL;

        $client = AppClient::getInstance();

        echo Color::colorize($client->version(), Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize("实例个数：" . count(AppClient::$_instance), Color::FG_GREEN) . PHP_EOL;
    }

}

