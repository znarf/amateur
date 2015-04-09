<?php namespace amateur;

class registry
{

  static $instances = [];

  static function instance($type, $name, $instance = null)
  {
    if (isset(self::$instances[$type][$name])) {
      return self::$instances[$type][$name];
    }
    elseif (is_object($instance)) {
      if ($instance instanceof \closure) {
        $instance = $instance();
      }
      return self::$instances[$type][$name] = $instance;
    }
    elseif (class_exists($instance)) {
      return self::$instances[$type][$name] = new $instance;
    }
    else {
       throw new exception("Unknown instance ({$type}/{$name}).");
     }
  }

  static function instances($type)
  {
    return isset(self::$instances[$type]) ? self::$instances[$type] : [];
  }

}
