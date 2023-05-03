<?php
namespace YZhanGateway\Auth;
use YZhanGateway\Exception\AuthException;
class TencentCloudAuth {
  private $secretId;
  private $secretKey;
  private $method;
  private $service;
  private $uri;
  private $queryString;
  private $headers = '';
  private $signedHeaders = '';
  private $body = '';
  private $timestamp;
  private $date;
  private $algorithm = 'TC3-HMAC-SHA256'; 

  function __construct(string $secretId, string $secretKey)
  {
      $this->secretId = $secretId;
      $this->secretKey = $secretKey;
  }

  function setMethod(string $method) {
    $this->method = $method;
  }

  function setService(string $service) {
    $this->service = $service;
  }
  
  function setUri(string $uri) {
    $this->uri = $uri;
  }
  
  function setQueryString(string $queryString) {
    $this->queryString = $queryString;
  }

  function setSignedHeaders(array $signHeaders) {
    ksort($signHeaders);
    $headers = '';
    $signedHeaders = array();
    foreach($signHeaders as $key => $val) {
      $key = strtolower($key);
      $headers .= $key . ':' . strtolower($val) . "\n";
      $signedHeaders []= $key;
    }
    $this->headers = $headers;
    $this->signedHeaders = implode(';', $signedHeaders);
  }

  function setBody(string $body) {
    $this->body = $body;
  }

  function setTimestamp(int $timestamp) {
    $this->timestamp = $timestamp;
    $this->date = gmdate('Y-m-d', $timestamp);
  }

  function getCanonicalRequest() {
    return $this->method . "\n" .
           $this->uri . "\n" .
           $this->queryString . "\n" .
           $this->headers . "\n" .
           $this->signedHeaders . "\n" .
           hash('SHA256', $this->body);
  }
  
  function getCredentialScope() {
    return $this->date . '/' . $this->service . '/tc3_request';
  }

  function getStringToSign() {
    return $this->algorithm . "\n" .
           $this->timestamp . "\n" .
           $this->getCredentialScope() . "\n" .
           hash('SHA256', $this->getCanonicalRequest());
  }

  function getSignature() {
    $secretDate = hash_hmac('SHA256', $this->date, 'TC3' . $this->secretKey, true);
    $secretService = hash_hmac('SHA256', $this->service, $secretDate, true);
    $secretSigning = hash_hmac('SHA256', 'tc3_request', $secretService, true);
    return hash_hmac('SHA256', $this->getStringToSign(), $secretSigning);
  }

  function getAuthorization() {
    return $this->algorithm .
           ' Credential=' . $this->secretId . '/' . $this->getCredentialScope() .
           ', SignedHeaders=' . $this->signedHeaders .
           ', Signature=' . $this->getSignature();
  }
}
?>