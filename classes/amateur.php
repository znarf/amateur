<?php namespace amateur;

class amateur
{

  static $registry = [];

  static function __callStatic($name, $args)
  {
    if (isset(replaceable::$replaceables[$name])) {
      $callable = replaceable::$replaceables[$name];
    }
    else {
      $callable = replaceable::get($name, $throw_exception = true);
    }
    return $callable(...$args);
  }

  function __call($name, $args)
  {
    if (isset(replaceable::$replaceables[$name])) {
      $callable = replaceable::$replaceables[$name];
    }
    else {
      $callable = replaceable::get($name, $throw_exception = true);
    }
    return $callable(...$args);
  }

  static function instance($classname = null)
  {
    # Get amateur itself
    if (!isset($classname)) {
      $classname = __class__;
    }
    # Already Registered
    if (isset(self::$registry['instances'][$classname])) {
      return self::$registry['instances'][$classname];
    }
    # Instanciate
    if (class_exists($classname)) {
      # Init
      if (!isset(self::$registry['instances'])) {
        self::$registry['instances'] = [];
      }
      return self::$registry['instances'][$classname] = new $classname;
    }
    # Unknown
    throw new exception("Unknown class ($classname).");
  }

}
