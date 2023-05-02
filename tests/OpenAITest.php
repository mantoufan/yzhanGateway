<?php
use YZhanGateway\Tool\TestCaseTool;
use YZhanGateway\YZhanGateway;

class OpenAITest extends TestCaseTool {
  public function testOpenAI() {
    $yzhanGateway = new YZhanGateway('OpenAI', array(
      'apiKey' => $_ENV['OPENAI_APIKEY']
    ));
    $res = $yzhanGateway->request(array(
      'method' => 'POST',
      'url' => 'https://api.openai.com/v1/completions',
      'postFields' => array(
        'model' => 'text-davinci-003',
        'prompt' => 'Hello',
        'temperature'=> 0
      )
    ));
    $this->assertNull($res[0]);
    $this->assertNotNull($res[1]['code']);
    $this->assertNotNull($res[1]['header']);
    $this->assertNotNull($res[1]['body']);
    $body = json_decode($res[1]['body'], true);
    $this->assertEquals($body['model'], 'text-davinci-003');
  }
}
?>