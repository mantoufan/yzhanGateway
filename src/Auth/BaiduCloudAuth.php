<?php
namespace YZhanGateway\Auth;
use YZhanGateway\Exception\AuthException;
class BaiduCloudAuth {
  private $ak;
  private $sk;
  private $version = "1";
  private $timestamp;
  private $expiration = 1800;
  private $method;
  private $uri;
  private $params = array();
  private $headers = array();
  private $needLog = false;

  function __construct($accessKey, $secretKey)
  {
      $this->ak = $accessKey;
      $this->sk = $secretKey;
      $date = new \DateTime('now');
      $date->setTimezone(new \DateTimeZone('UTC'));
      $this->timestamp = $date->format('Y-m-d\TH:i:s\Z');
  }

  public function setVersion($version)
  {
      $this->version = $version;
  }

  public function setExpiration($expiration)
  {
      $this->expiration = $expiration;
  }

  public function setMethod($method)
  {
      if (!empty($method)) {
          $this->method = strtoupper($method);
      }
  }

  public function setTimestamp($timestamp)
  {
      $this->timestamp = $timestamp;
  }

  public function setUri($uri)
  {
      $this->uri = $uri;
  }

  public function setParams($params)
  {
      $this->params = $this->normalizeParam($params);
  }

  public function setSignHeaders($headers)
  {
      $this->headers = $this->normalizeHeaders($headers);
  }

  public function beLog($needLog)
  {
      $this->needLog = $needLog;
  }

  public function genAuthorization()
  {
      $signature = $this->genSignature();
      $authStr = "bce-auth-v" . $this->version . "/" .
          $this->ak . "/" . $this->timestamp . "/" .
          $this->expiration . "/" . $this->getSignedHeaderNames() . "/" . $signature;
      return $authStr;
  }

  public function genSignature()
  {
      if (empty($this->method)) {
          throw new AuthException("method is null or empty");
      }
      $signingKey = $this->genSigningKey();
      $this->signerLog("signingKey:" . $signingKey, __LINE__, __FILE__);
      $authStr = $this->method . "\n" .
          $this->getCanonicalURI() . "\n" .
          $this->getCanonicalParam() . "\n" .
          $this->getCanonicalHeaders();
      $this->signerLog("auth str:" . $authStr, __LINE__, __FILE__);
      return $this->sha256($signingKey, $authStr);
  }

  public function genSigningKey()
  {
      if (empty($this->ak)) {
          throw new AuthException("Access key is null or empty");
      }
      if (empty($this->sk)) {
          throw new AuthException("Secret key is null or empty");
      }
      if (empty($this->version)) {
          throw new AuthException("Version is null or empty");
      }
      if (empty($this->timestamp)) {
          throw new AuthException("Timestamp is null or empty");
      }
      if (empty($this->expiration)) {
          throw new AuthException("Expiration is null or empty");
      }
      $authStr = "bce-auth-v" . $this->version . "/" . $this->ak . "/" .
          $this->timestamp . "/" . $this->expiration;
      return $this->sha256($this->sk, $authStr);
  }

  public function getCanonicalParam()
  {
    if (empty($this->params)) {
        return "";
    }
    $arryLen = count($this->params);
    $canonicalParams = "";
    foreach ($this->params as $key => $value) {
        if (is_array($value)) {
            $num = count($value);
            if (count($value) == 0) {
                $canonicalParams = $canonicalParams . $key . "=";
            } else {
                foreach ($value as $item) {
                    $canonicalParams = $canonicalParams . $key . "=" . $item;
                    if ($num > 1) {
                        $canonicalParams = $canonicalParams . "&";
                        $num--;
                    }
                }
            }
        } else {
            $canonicalParams = $canonicalParams . $key . "=" . $value;
        }
        if ($arryLen > 1) {
            $canonicalParams = $canonicalParams . "&";
            $arryLen--;
        }
    }
    return $canonicalParams;
  }

  public function getCanonicalURI()
  {
    if (empty($this->uri)) {
        throw new AuthException("Uri is null or empty");
    }
    $newUri = $this->dataEncode($this->uri, true);
    if (strpos($newUri, "/") === 0) {
        return $newUri;
    }
    return "/" . $newUri;
  }

  public function getCanonicalHeaders()
  {
    if (empty($this->headers) || !array_key_exists("host", $this->headers)) {
        throw new AuthException("host not in headers");
    }
    $canonicalHeaders = "";
    $strArry = array();
    foreach ($this->headers as $key => $value) {
        if (empty($value)) {
            continue;
        }
        $strArry[] = $this->dataEncode($key, false) . ":" . $value;
    }
    $arryLen = count($strArry);
    for ($i = 0; $i < $arryLen; $i++) {
        if ($i < $arryLen - 1) {
            $canonicalHeaders = $canonicalHeaders . $strArry[$i] . "\n";
            continue;
        }
        $canonicalHeaders = $canonicalHeaders . $strArry[$i];
    }
    return $canonicalHeaders;
  }

  private function sha256($key, $data)
  {
    return hash_hmac('sha256', $data, $key);
  }

  private function dataEncode($data, $isPath)
  {
    if (empty($data)) {
        return "";
    }
    $encode = mb_detect_encoding($data, array("ASCII", "UTF-8", "GB2312", "GBK", "BIG5"));
    if ($encode != "UTF-8") {
        $data = mb_convert_encoding($data, 'utf-8', $encode);
    }
    $encodeStr = rawurlencode($data);
    if ($isPath) {
        $encodeStr = str_replace("%2F", "/", $encodeStr);
    }
    return $encodeStr;
  }

  private function normalizeHeaders($headers)
  {
    $newArray = array();
    if (empty($headers)) {
        return $newArray;
    }
    foreach ($headers as $key => $value) {
        $newKey = strtolower($key);
        if (empty($newKey)) {
            continue;
        }
        $newArray[$newKey] = $this->dataEncode(trim($value), false);
    }
    ksort($newArray);
    return $newArray;
  }

  private function normalizeParam($params)
  {
    $newArray = array();
    if (empty($params)) {
        return $newArray;
    }
    foreach ($params as $key => $value) {
        if (empty($key) || strtolower($key) == "authorization") {
            continue;
        }
        if (is_array($value)) {
            $newSubArray = array();
            foreach ($value as $item) {
                $newSubArray[] = $this->dataEncode($item, false);
            }
            sort($newSubArray);
            $newArray[$this->dataEncode($key, false)] = $newSubArray;
        } else {
            $newArray[$this->dataEncode($key, false)] = $this->dataEncode($value, false);
        }
    }
    ksort($newArray);
    return $newArray;
  }

  private function getSignedHeaderNames()
  {
    $arryLen = count($this->headers);
    $headerNames = "";
    foreach ($this->headers as $key => $value) {
        $headerNames = $headerNames . $key;
        if ($arryLen > 1) {
            $headerNames = $headerNames . ";";
            $arryLen--;
        }
    }
    return $headerNames;
  }

  private function signerLog($content, $line, $file)
  {
    if ($this->needLog) {
        error_log($file . ":" . $line . ":[" . $content . "]\n", 3, "./signer_log");
    }
  }
}
?>