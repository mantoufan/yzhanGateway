<?php
namespace YZhanGateway;
class YZhanGateway {
  private $client;
  public function __construct(string $clientName, array $params) {
    $className = 'YZhanGateway\\Client\\' . $clientName . 'Client';
    $this->client = new $className($params);
  }
  public function request(array $params) {
    return $this->client->request($params);
  }
}
?>