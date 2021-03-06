<?php
namespace Xin\Thrift\MicroService;
/**
 * Autogenerated by Thrift Compiler (0.11.0)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
use Thrift\Base\TBase;
use Thrift\Type\TType;
use Thrift\Type\TMessageType;
use Thrift\Exception\TException;
use Thrift\Exception\TProtocolException;
use Thrift\Protocol\TProtocol;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Exception\TApplicationException;


interface AppIf {
  /**
   * @param \Xin\Thrift\ZipkinService\Options $options
   * @return string
   * @throws \Xin\Thrift\ZipkinService\ThriftException
   */
  public function version(\Xin\Thrift\ZipkinService\Options $options);
  /**
   * @param \Xin\Thrift\ZipkinService\Options $options
   * @return string
   * @throws \Xin\Thrift\ZipkinService\ThriftException
   */
  public function testException(\Xin\Thrift\ZipkinService\Options $options);
  /**
   * @param \Xin\Thrift\ZipkinService\Options $options
   * @return string
   * @throws \Xin\Thrift\ZipkinService\ThriftException
   */
  public function welcome(\Xin\Thrift\ZipkinService\Options $options);
  /**
   * @param \Xin\Thrift\ZipkinService\Options $options
   * @return string
   * @throws \Xin\Thrift\ZipkinService\ThriftException
   */
  public function timeout(\Xin\Thrift\ZipkinService\Options $options);
}


