<?php
namespace YZhanGateway\Exception;
class AuthException extends \Exception {
  function __construct($message) {
    parent::__construct($message, -1);
  }
}
?>