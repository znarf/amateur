<?php namespace amateur;

class registry
{

  static $instances = [];

  static function instance($classname)
  {
    # Already Registered
    if (isset(self::$instances[$classname])) {
      return self::$instances[$classname];
    }
    # Instanciate
    if (class_exists($classname)) {
      return self::$instances[$classname] = new $classname;
    }
    # Unknown
    throw new exception("Unknown class ($classname).");
  }

}
