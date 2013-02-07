<?php

namespace Core;

class Response
{

  static function status($code, $message)
  {
    header("HTTP/1.1 $code $message");
  }

  static function ok($content)
  {
    echo $content;
  }

  static function set_header($name, $value)
  {
    header("$name:$value");
  }

}
