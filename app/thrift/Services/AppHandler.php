<?php
// +----------------------------------------------------------------------
// | AppHandler.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Thrift\Services;

use App\Thrift\Services\Impl\AppHandlerImpl;
use Xin\Thrift\MicroService\AppIf;
use Xin\Thrift\MicroService\ThriftException;
use Xin\Thrift\ZipkinService\Options;

class AppHandler extends Handler implements AppIf
{

    protected $impl = AppHandlerImpl::class;

    /**
     * @desc   返回项目版本号
     * @author limx
     * @return mixed
     * @throws ThriftException
     */
    public function version(Options $options)
    {
        return parent::version($options);
    }

    /**
     * @desc   欢迎语
     * @author limx
     * @param Options $options
     */
    public function welcome(Options $options)
    {
        return parent::welcome($options);
    }

    /**
     * @desc   测试异常抛出
     * @author limx
     * @throws ThriftException
     */
    public function testException(Options $options)
    {
        return parent::testException($options);
    }

    /**
     * @desc   延迟测试
     * @author limx
     * @param Options $options
     * @return mixed
     */
    public function timeout(Options $options)
    {
        return parent::timeout($options);
    }
}