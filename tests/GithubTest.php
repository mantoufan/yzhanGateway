<?php
use YZhanGateway\Tool\TestCaseTool;
use YZhanGateway\YZhanGateway;

class GithubTest extends TestCaseTool {
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