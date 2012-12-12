<?php

namespace Core;

class Request
{

  static $url;

  static function url($value = null)
  {
    global $app;
    // Set
    if ($value !== null) return self::$url = $value;
    // Get
    if (!self::$url) {
      $request_uri = strtok($_SERVER['REQUEST_URI'], '?');
      self::$url = str_replace($app->path(), '', $request_uri);
    }
    return self::$url;
  }

  static function method()
  {
    return isset($_REQUEST['forceMethod']) ? $_REQUEST['forceMethod'] : $_SERVER['REQUEST_METHOD'];
  }

  static function host()
  {
    return $_SERVER['HTTP_HOST'];
  }

  static function param($name, $value = null)
  {
    if ($value !== null) return $_REQUEST[$name] = $value;
    if (isset($_REQUEST[$name])) return $_REQUEST[$name];
  }

  static $headers = [];

  static function header($name)
  {
    if (array_key_exists($name, self::$headers)) {
      return self::$headers[$name];
    } else {
      $key = 'HTTP_' . str_replace('-', '_', strtoupper($name));
      return self::$headers[$name] = isset($_SERVER[$key]) ? $_SERVER[$key] : null;
    }
  }

  static function url_is($str)
  {
    return $str == self::url();
  }

  static function url_start_with($str)
  {
    return strpos(self::url(), $str) === 0;
  }

  static function url_match($route)
  {
    $route = "^$route$";
    $route = str_replace('/', '\/', $route);
    $route = str_replace('*', '([^\/]+)', $route);
    $result = preg_match("/$route/", self::url(), $matches);
    return $result ? $matches : false;
  }

}

