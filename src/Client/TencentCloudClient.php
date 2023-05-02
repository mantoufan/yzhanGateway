<?php
namespace YZhanGateway\Client;
use YZhanGateway\Tool\ClientTool;
use YZhanGateway\Auth\TencentCloudAuth;
use YZhanGateway\Exception\AuthException;
class TencentCloudClient {
  private $auth;
  public function __construct(array $params) {
    $this->auth = new TencentCloudAuth($params['secretId'], $params['secretKey']);
  }
  public function request(array $params) : array {
    $parsedUrl = parse_url($params['url']);
    $host = $parsedUrl['host'];
    $uri = empty($parsedUrl['path']) ? '/' : $parsedUrl['path'];
    $queryString = empty($parsedUrl['query']) ? '' : $parsedUrl['query'];
    ['method' => $method, 'action' => $action, 'version' => $version, 'region' => $region] = $params;
    $postFields = empty($params['postFields']) ? array() : $params['postFields'];
    $httpHeaders = empty($params['httpHeaders']) ? array() : $params['httpHeaders'];
    $contentType = empty($httpHeaders['content-type']) ? 'application/json' : $httpHeaders['content-type'];
    $httpHeaders = array_merge(array(
      'host' => $host,
      'content-type' => 'application/json'
    ), empty($httpHeaders) ? array() : $httpHeaders);
    $postFields = json_encode($postFields, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $timestamp = time();
    [$service] = explode('.', $host);
    $this->auth->setService($service);
    $this->auth->setMethod($method);
    $this->auth->setUri($uri);
    $this->auth->setQueryString($queryString);
    $this->auth->setSignedHeaders(array(
      'host'=> $host,
      'content-type' => $httpHeaders['content-type']
    ));
    $this->auth->setBody($postFields);
    $this->auth->setTimestamp($timestamp);
    try {
      $authorization = $this->auth->getAuthorization();
    } catch (AuthException $e) {
      return array($e->getMessage());
    }
    $params['httpHeaders'] = array_merge(array(
      'X-TC-Action' => $action,
      'X-TC-Region' => $region,
      'X-TC-Timestamp' => $timestamp,
      'X-TC-Version' => $version,
      'Authorization' => $authorization,
    ), $httpHeaders);
    $params['postFields'] =  $postFields;
    return array(null, ClientTool::Request($params));
  }
}
?>