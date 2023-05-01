<?php
use YZhanGateway\Tool\TestCaseTool;
use YZhanGateway\YZhanGateway;

class CloudflareTest extends TestCaseTool {
  public function testCloudflare() {
    $yzhanGateway = new YZhanGateway('Cloudflare', array(
      'apiToken' => $_ENV['CLOUDFLARE_APITOKEN']
    ));
    $res = $yzhanGateway->request(array(
      'method' => 'POST',
      'url' => 'https://api.cloudflare.com/client/v4/zones/' . $_ENV['CLOUDFLARE_REGION_ID'] . '/purge_cache',
      'postFields' => array(
        'files' => array($_ENV['CLOUDFLARE_TEST_URL'])
      )
    ));
    $this->assertNull($res[0]);
    $this->assertNotNull($res[1]['code']);
    $this->assertNotNull($res[1]['header']);
    $this->assertNotNull($res[1]['body']);
  }
}
?>