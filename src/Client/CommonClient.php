<?php
namespace YZhanGateway\Client;
use YZhanGateway\Tool\ClientTool;
class CommonClient {
  public function request(array $params) : array {
    return array(null, ClientTool::Request($params));
  }
}
?>