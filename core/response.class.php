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

  static function redirect($path, $permanent = false)
  {
    global $app;
    $url = strpos($path, '://') !== false ? $path : $app->path() . $path;
    self::status($permanent ? 301 : 302);
    self::set_header("Location", $url);
    exit;
  }

}
