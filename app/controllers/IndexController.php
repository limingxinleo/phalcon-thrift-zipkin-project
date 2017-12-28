<?php
// +----------------------------------------------------------------------
// | 默认控制器 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
namespace App\Controllers;

use App\Common\Zipkin\ZipkinClient;
use App\Thrift\Clients\AppClient;

class IndexController extends Controller
{
    /**
     * @desc
     * @author limx
     * @return bool|\Phalcon\Mvc\View
     * @Middleware('auth')
     */
    public function indexAction()
    {
        $version = AppClient::getInstance()->version(ZipkinClient::getInstance()->options);
        $message = AppClient::getInstance()->welcome(ZipkinClient::getInstance()->options);
        if ($this->request->isPost()) {
            return $this->response->setJsonContent([
                'version' => $version,
                'message' => $message,
                'welcome' => "You're now flying with Phalcon. Great things are about to happen!",
            ]);
        }
        $this->view->version = $version;
        return $this->view->render('index', 'index');
    }

}