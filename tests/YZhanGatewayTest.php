<?php
use YZhanGateway\Tool\TestCaseTool;
use YZhanGateway\YZhanGateway;

class YZhanGatewayTest extends TestCaseTool {
  public function testCommon() {
    $yzhanGateway = new YZhanGateway('Common');
    $res = $yzhanGateway->request(array(
      'method' => 'GET',
      'url' => 'https://animechan.vercel.app/api/random'
    ));
    $this->assertNull($res[0]);
    $this->assertNotNull($res[1]['code']);
    $this->assertNotNull($res[1]['header']);
    $this->assertNotNull($res[1]['body']);
  }
  public function testCache() {
    $yzhanGateway = new YZhanGateway('Common');
    $res = $yzhanGateway->cache()->request(array(
      'method' => 'GET',
      'url' => 'https://animechan.vercel.app/api/random',
      'cache' => array(
        'maxAge' => 86400
      ) 
    ));
    $this->assertNull($res[0]);
    $this->assertNotNull($res[1]['code']);
    $this->assertNotNull($res[1]['header']);
    $this->assertNotNull($res[1]['body']);
  }
}
?>