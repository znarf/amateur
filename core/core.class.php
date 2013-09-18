<?php namespace amateur\core;

class core
{

  static $app;

  static $request;

  static $response;

  static function instance($name, $value = null)
  {
    if ($value) {
      return $GLOBALS[$name] = self::$$name = $value;
    }
    if (empty(self::$$name)) {
      $classname = __namespace__ . '\\' . $name;
      $GLOBALS[$name] = self::$$name = new $classname;
    }
    return self::$$name;
  }

}
