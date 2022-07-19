<?php
namespace YZhanGateway\Client;
use YZhanGateway\Tool\ClientTool;
use YZhanGateway\Auth\BaiduCloudAuth;
use YZhanGateway\Exception\AuthException;
class BaiduCloudClient {
  private $auth;
  public function __construct(array $params) {
    $this->auth = new BaiduCloudAuth($params['accessKey'], $params['secretKey']);
  }
  public function request(array $params) : array {
    ['host' => $host, 'path' => $uri] = parse_url($params['url']);
    $this->auth->setMethod($params['method']);
    $this->auth->setUri($uri);
    $this->auth->setSignHeaders(array(
      'host'=> $host
    ));
    try {
      $authorization = $this->auth->genAuthorization();
    } catch (AuthException $e) {
      return array($e->getMessage());
    }
    $params['httpHeaders'] = array_merge(array(
      'host' => $host,
      'authorization' => $authorization,
      'content-type' => 'application/json'
    ), (array) $params['httpHeaders']);
    $params['postFields'] = json_encode($params['postFields'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return array(null, ClientTool::Request($params));
  }
}
?>