<?php

namespace Core;

class Url
{

  static $url = null;

  static function get()
  {
    return isset(self::$url) ? self::$url : self::$url = strtok($_SERVER['REQUEST_URI'], '?');
  }

  static function base_path($value = null)
  {
    static $base_path = null;
    if (isset($value)) {
      self::$url = str_replace($value, '', self::get());
      $base_path = $value;
    }
    return $base_path ? $base_path : '';
  }

  static function is($string)
  {
    return $string == self::get();
  }

  static function start_with($string)
  {
    return strpos(self::get(), $string) === 0;
  }

  static function match($route, &$matches)
  {
    $route = "^$route$";
    $route = str_replace('/', '\/', $route);
    $route = str_replace('*', '([^\/]+)', $route);
    return preg_match("/$route/", self::get(), $matches);
  }

}
