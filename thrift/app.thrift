namespace php Xin.Thrift.MicroService
namespace go vendor.service

include 'zipkin.thrift'

service App {
    // 返回项目版本号
    string version(1: zipkin.Options options) throws (1:zipkin.ThriftException ex)

    // 测试异常抛出
    string testException(1: zipkin.Options options) throws(1:zipkin.ThriftException ex)

    // 欢迎语
    string welcome (1: zipkin.Options options) throws (1:zipkin.ThriftException ex)
}