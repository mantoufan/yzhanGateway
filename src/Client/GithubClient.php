<?php
namespace YZhanGateway\Client;
use YZhanGateway\Tool\ClientTool;
class GithubClient {
  private $accessToken;
  private $userAgent;
  public function __construct(array $params) {
    $this->accessToken = $params['accessToken'];
    $this->userAgent = $params['userAgent'];
  }
  public function request(array $params) : array {
    $params['httpHeaders'] = array_merge(array(
      'user-agent' => $this->userAgent,
      'accept' => 'application/vnd.github+json',
      'authorization' => 'Bearer ' . $this->accessToken,
      'content-type' => 'application/json'
    ), empty($params['httpHeaders']) ? array() : $params['httpHeaders']);
    //$params['postFields'] = json_encode($params['postFields'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return array(null, ClientTool::Request($params));
  }
}
?>