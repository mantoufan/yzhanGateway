<?php
namespace YZhanGateway\Client;
use YZhanGateway\Tool\ClientTool;
class CloudflareClient {
  private $apiToken;
  public function __construct(array $params) {
    $this->apiToken = $params['apiToken'];
  }
  public function request(array $params) : array {
    $params['httpHeaders'] = array_merge(array(
      'authorization' => 'Bearer ' . $this->apiToken,
      'content-type' => 'application/json'
    ), empty($params['httpHeaders']) ? array() : $params['httpHeaders']);
    $params['postFields'] = json_encode($params['postFields'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return array(null, ClientTool::Request($params));
  }
}
?>