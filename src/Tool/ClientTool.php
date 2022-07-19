<?php
namespace YZhanGateway\Tool;
class ClientTool {
  static public function Request(array $params) {
    $ch = curl_init();
    curl_setopt_array($ch, array(
      CURLOPT_URL => $params['url'],
      CURLOPT_CUSTOMREQUEST => $params['customRequest'],
      CURLOPT_HTTPHEADER => array_map(function($v, $k) {
        return $k . ':' . $v;
      }, array_values($params['httpHeaders']), array_keys($params['httpHeaders'])),
      CURLOPT_POST => $params['customRequest'] === 'POST',
      CURLOPT_POSTFIELDS => $params['postFields'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 6,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_HEADER => true,
    ));
    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    return array(
      'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
      'header' => substr($response, 0, $header_size),
      'body' => substr($response, $header_size)
    );
  }
}
?>