<?php

namespace Core;

class Response
{

  static function exception($e)
  {
    error($e->getCode(), $e->getMessage());
  }

  static function status($code, $message)
  {
    header("HTTP/1.1 $code $message");
  }

  static function set_header($name, $value)
  {
    header("$name:$value");
  }

}
