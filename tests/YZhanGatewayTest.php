<?php
use PHPUnit\Framework\TestCase;
use YZhanGateway\YZhanGateway;
use Dotenv\Dotenv;

class YZhanGatewayTest extends TestCase {
  public static function setUpBeforeClass(): void {
    Dotenv::createImmutable(__DIR__ . '/..', '.env.testing')->load();
  }
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
  public function testGithub() {
    $yzhanGateway = new YZhanGateway('Github', array(
      'accessToken' => $_ENV['GITHUB_ACCESS_TOKEN'],
      'userAgent' => $_ENV['GITHUB_USER_NAME']
    ));
    $res = $yzhanGateway->request(array(
      'method' => 'GET',
      'url' => 'https://api.github.com/users/' . $_ENV['GITHUB_USER_NAME'] . '/events'
    ));
    $this->assertNull($res[0]);
    $this->assertNotNull($res[1]['code']);
    $this->assertNotNull($res[1]['header']);
    $this->assertNotNull($res[1]['body']);
  }
}
?>