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
#### {root}/.env.test
运行 `composer test` 前，请将`.env.test.template`重命名为`.env.test`存放测试需要的变量  
Before running `composer test`, rename `.env.test.template` to `env.test` to store variables needed for testing  
![.env.test example](https://s2.loli.net/2022/09/10/1e7GxSlquyTPdRX.jpg)
## Example 示例
### Common 通用
```php
$yzhanGateway = new YZhanGateway('Common');
$res = $yzhanGateway->request(array(
  'method' => 'GET',
  'url' => 'https://animechan.vercel.app/api/random'
));
```
### Use Cache 使用缓存
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
### BaiduCloud 百度智能云
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
### Cloudflare
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
### Github
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
### OpenAI
Chat using text-davinci
```php
$yzhanGateway = new YZhanGateway('OpenAI', array(
  'apiKey' => $_ENV['OPENAI_APIKEY'],
  // 'organization' => $_ENV['OPENAI_ORGANIZATION'] // Optional
));
$res = $yzhanGateway->request(array(
  'method' => 'POST',
  'url' => 'https://api.openai.com/v1/completions',
  'postFields' => array(
    'model' => 'text-davinci-003',
    'prompt' => 'Hello',
    'temperature'=> 0 // Optional, 0 means the most certain results
  )
));
```
### TencentCloud 腾讯云
Get CVM list
```php
$yzhanGateway = new YZhanGateway('TencentCloud', array(
  'secretId' => $_ENV['TENCENTCLOUD_SECRET_ID'],
  'secretKey' => $_ENV['TENCENTCLOUD_SECRET_KEY']
));
$res = $yzhanGateway->request(array(
  'method' => 'POST',
  'url' => 'https://cvm.tencentcloudapi.com',
  'action' => 'DescribeInstances',
  'version' => '2017-03-12',
  'region' => 'ap-guangzhou',
  'postFields' => array(
    'Limit' => 1,
    'Filters' => array(
      array('Values' => array('未命名'), 'Name' => 'instance-name')
    ),
  )
));
```