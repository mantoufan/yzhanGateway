<?php
namespace YZhanGateway\Tool;
class ClientTool {
  static public function Request(array $params) {
    $ch = curl_init();
    curl_setopt_array($ch, array(
      CURLOPT_URL => $params['url'],
      CURLOPT_CUSTOMREQUEST => $params['method'],
      CURLOPT_HTTPHEADER => empty($params['httpHeaders']) ? array() : array_map(function ($v, $k) {
        return $k . ':' . $v;
      }, array_values((array) $params['httpHeaders']), array_keys((array) $params['httpHeaders'])),
      CURLOPT_POST => $params['method'] === 'POST',
      CURLOPT_POSTFIELDS => empty($params['postFields']) ? null : $params['postFields'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => $params['timeout'] ?? 6,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_HEADER => true,
    ));
    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    return array(
      'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
      'header' => substr($response, 0, $header_size),
      'body' => substr($response, $header_size),
    );
  }
}
?>