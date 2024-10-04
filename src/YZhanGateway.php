<?php
namespace YZhanGateway;
use YZhanCache\YZhanCache;

class YZhanGateway {
  private $client;
  private $yzhanCache;
  public function __construct(string $clientName, array $params = null) {
    $className = 'YZhanGateway\\Client\\' . $clientName . 'Client';
    $this->client = new $className($params);
  }
  public function cache(string $type = 'File', array $params = array()) {
    $this->yzhanCache = new yzhanCache($type, $params);
    return $this;
  }
  public function request(array $params) {
    if ($this->yzhanCache === null) {
      return $this->client->request($params);
    }
    $key = $this->getKey($params);
    if ($this->yzhanCache->has($key) === false) {
      $maxAge = empty($params['cache']) === false ? $params['cache']['maxAge'] : null;
      $this->yzhanCache->set($key, $this->client->request($params), $maxAge);
    }
    return $this->yzhanCache->get($key);
  }
  public function getCache() {
    return $this->yzhanCache;
  }
  public function getKey(array $params) {
    return md5(serialize($params));
  }
}
?>