<?php
use YZhanGateway\Tool\TestCaseTool;
use YZhanGateway\YZhanGateway;

class BaiduCloudTest extends TestCaseTool {
  public function testBaiduCloud() {
    $yzhanGateway = new YZhanGateway('BaiduCloud', array(
      'accessKey' => $_ENV['BAIDUCLOUD_ACCESSKEY'],
      'secretKey' => $_ENV['BAIDUCLOUD_SECRETKEY']
    ));
    $res = $yzhanGateway->request(array(
      'method' => 'POST',
      'url' => 'http://cdn.baidubce.com/v2/cache/purge',
      'postFields' => array(
        'tasks' => array(
          array('url' => $_ENV['BAIDUCLOUD_TEST_URL'])
        )
      )
    ));
    $this->assertNull($res[0]);
    $this->assertNotNull($res[1]['code']);
    $this->assertNotNull($res[1]['header']);
    $this->assertNotNull($res[1]['body']);
  }
}
?>