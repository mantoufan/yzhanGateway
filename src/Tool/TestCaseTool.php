<?php
namespace YZhanGateway\Tool;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

class TestCaseTool extends TestCase {
  public static function setUpBeforeClass(): void {
    Dotenv::createImmutable(__DIR__ . '/../..', '.env.test')->load();
  }
}
?>