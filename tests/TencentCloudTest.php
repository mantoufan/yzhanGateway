<?php
use YZhanGateway\Tool\TestCaseTool;
use YZhanGateway\YZhanGateway;

class TencentCloudTest extends TestCaseTool {
  public function testTencentCloud() {
    $yzhanGateway = new YZhanGateway('TencentCloud', array(
      'secretId' => $_ENV['TENCENTCLOUD_SECRET_ID'],
      'secretKey' => $_ENV['TENCENTCLOUD_SECRET_KEY']
    ));
    $res = $yzhanGateway->request(array(
      'method' => 'POST',
      'url' => 'https://cvm.tencentcloudapi.com',
      'action' => 'DescribeInstances',
      'version' => '2017-03-12',
      'region' => 'ap-guangzhou',
      'postFields' => array(
        'Limit' => 1,
        'Filters' => array(
          array('Values' => array('未命名'), 'Name' => 'instance-name')
        ),
      )
    ));
    $this->assertNull($res[0]);
    $this->assertNotNull($res[1]['code']);
    $this->assertNotNull($res[1]['header']);
    $this->assertNotNull($res[1]['body']);
    $body = json_decode($res[1]['body'], true);
    $this->assertIsInt($body['Response']['TotalCount']);
  }
}
?>