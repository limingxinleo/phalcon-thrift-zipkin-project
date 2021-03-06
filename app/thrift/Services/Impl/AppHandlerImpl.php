<?php
// +----------------------------------------------------------------------
// | AppHandler.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Thrift\Services\Impl;

use App\Thrift\Clients\AppClient;
use Xin\Thrift\MicroService\AppIf;
use Xin\Thrift\ZipkinService\Options;
use Xin\Thrift\ZipkinService\ThriftException;

class AppHandlerImpl extends ImplHandler implements AppIf
{
    /**
     * @desc   返回项目版本号
     * @author limx
     * @return mixed
     * @throws ThriftException
     */
    public function version(Options $options)
    {
        return $this->config->version;
    }

    /**
     * @desc   欢迎语
     * @author limx
     * @param Options $options
     */
    public function welcome(Options $options)
    {
        $version = AppClient::getInstance()->version();
        return "You're using limingxinleo\phalcon-project {$version}";
    }

    /**
     * @desc   测试异常抛出
     * @author limx
     * @throws ThriftException
     */
    public function testException(Options $options)
    {
        throw new ThriftException([
            'code' => '400',
            'message' => '异常测试'
        ]);
    }

    /**
     * @desc   延迟测试
     * @author limx
     * @param Options $options
     * @return mixed
     */
    public function timeout(Options $options)
    {
        sleep(1);
        return $this->config->version;
    }
}