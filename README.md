# yzhanGateway
Developing PHP SDK for any API.  
为任何 API 快速开发 PHP SDK.  
## Install 安装  
```shell
composer require mantoufan/yzhangateway
```
## Usage 使用
### {root}/src
#### Client
1. Create a `{client name}.php` in `src\Client` Directory  
1. 在 `src\Client` 目录新建一个 `{客户端名称}.php`
2. Implement `request` method
2. 实现请求 `request` 方法
#### Auth
如需要，新建 `.php` 提供鉴权类  
例如包含获取 `authorization` 请求头的方法    
If necessary, create a new `.php` to provide an authentication class,  
such as a method to obtain the `authorization` request header  
#### Exception
如需要，新建 `.php` 声明新错误类型  
If necessary, create a new `.php` here to declare a new error type  
#### Tool
##### ClientTool
提供 `Request` 静态方法，发出请求和响应  
Provides the `Request` static method for making requests and responses  
### {root}/tests
#### {root}/.env.testing
运行 `composer test` 前，请将`.env.testing.template`重命名为`.env.testing`存放测试需要的变量  
Before running `composer test`, rename `.env.testing.template` to `env.testing` to store variables needed for testing  
![.env.testing example](https://s2.loli.net/2022/09/10/1e7GxSlquyTPdRX.jpg)
#### Example
##### Common
```php
$yzhanGateway = new YZhanGateway('Common');
$res = $yzhanGateway->request(array(
  'method' => 'GET',
  'url' => 'https://animechan.vercel.app/api/random'
));
```
##### Use Cache
Cache Results for 86400 seconds
```php
$yzhanGateway = new YZhanGateway('Common');
$res = $yzhanGateway->cache()->request(array(
  'method' => 'GET',
  'url' => 'https://animechan.vercel.app/api/random',
  'cache' => array(
    'maxAge' => 86400
  ) 
));
```
##### BaiduCloud
Purge Files by urls in Biadu Cloud CDN.  
```php
$yzhanGateway = new YZhanGateway('BaiduCloud', array(
  'accessKey' => $_ENV['BAIDUCLOUD_ACCESSKEY'],
  'secretKey' => $_ENV['BAIDUCLOUD_SECRETKEY']
));
$res = $yzhanGateway->request(array(
  'method' => 'POST',
  'url' => 'http://cdn.baidubce.com/v2/cache/purge',
  'postFields' => array(
    'tasks' => array(
      array('url' => $_ENV['BAIDUCLOUD_TEST_URL'])
    )
  )
));
```
##### Cloudflare
Purge Files by urls (<= 30) in Cloudflare.  
```php
$yzhanGateway = new YZhanGateway('Cloudflare', array(
  'apiToken' => $_ENV['CLOUDFLARE_APITOKEN']
));
$res = $yzhanGateway->request(array(
  'method' => 'POST',
  'url' => 'https://api.cloudflare.com/client/v4/zones/' . $_ENV['CLOUDFLARE_REGION_ID'] . '/purge_cache',
  'postFields' => array(
    'files' => array($_ENV['CLOUDFLARE_TEST_URL'])
  )
));
```
##### Github
Get user's recent activities.
```php
$yzhanGateway = new YZhanGateway('Github', array(
  'accessToken' => $_ENV['GITHUB_ACCESS_TOKEN'],
  'userAgent' => $_ENV['GITHUB_USER_NAME']
));
$res = $yzhanGateway->request(array(
  'method' => 'GET',
  'url' => 'https://api.github.com/users/' . $_ENV['GITHUB_USER_NAME'] . '/events'
));
```