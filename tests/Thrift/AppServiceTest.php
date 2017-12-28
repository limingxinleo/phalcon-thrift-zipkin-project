<?php
// +----------------------------------------------------------------------
// | BaseTest.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace Tests\Thrift;

use App\Thrift\Clients\AppClient;

/**
 * Class UnitTest
 */
class AppServiceTest extends BaseTest
{
    public function testBaseCase()
    {
        $this->assertTrue(
            extension_loaded('phalcon')
        );

        $this->assertTrue(
            extension_loaded('swoole')
        );
    }

    public function testVersionCase()
    {
        $this->begin('testVersionCase');
        $version = di('config')->version;
        $client = AppClient::getInstance();
        $this->assertEquals($version, $client->version());
        $this->end();
    }

    public function testWelcomeCase()
    {
        $this->begin('testWelcomeCase');
        $version = di('config')->version;
        $client = AppClient::getInstance();
        $this->assertEquals("You're using limingxinleo\phalcon-project {$version}", $client->welcome());
        $this->end();
    }

    public function testManyRequestCase()
    {
        $this->begin('testManyRequestCase');
        $client = AppClient::getInstance();
        $time = time();
        for ($i = 0; $i < 10000; $i++) {
            $client->version();
        }
        $this->assertTrue(time() - $time < 9);
        $this->end();
    }

    public function testExceptionCase()
    {
        $this->begin('testManyRequestCase');
        try {
            $client = AppClient::getInstance()->testException();
        } catch (\Exception $ex) {
            $this->assertEquals(400, $ex->getCode());
            $this->assertEquals('异常测试', $ex->getMessage());
        }
        $this->end();
    }
}