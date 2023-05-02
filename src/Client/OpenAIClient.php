<?php
namespace YZhanGateway\Client;
use YZhanGateway\Tool\ClientTool;
class OpenAIClient {
  private $apiKey;
  private $organization;
  public function __construct(array $params) {
    $this->apiKey = $params['apiKey'];
    $this->organization = empty($params['organization']) ? 'org-XIJ1T48BCBhG1ee7PiaAlIpI' : $params['organization'];
  }
  public function request(array $params) : array {
    $params['httpHeaders'] = array_merge(array(
      'authorization' => 'Bearer ' . $this->apiKey,
      'content-type' => 'application/json',
      'openai-organization' => $this->organization
    ), empty($params['httpHeaders']) ? array() : $params['httpHeaders']);
    $params['postFields'] = json_encode($params['postFields'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return array(null, ClientTool::Request($params));
  }
}
?>