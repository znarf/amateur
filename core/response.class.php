<?php

namespace Amateur\Core;

class Response
{

  static function status($code)
  {
    http_response_code($code);
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
