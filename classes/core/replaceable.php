<?php namespace amateur\core;

class replaceable
{

  static $replaceables = [];

  static function load($dir)
  {
    foreach (new \DirectoryIterator($dir) as $file) {
      if ($file->isDir() && !$file->isDot()) {
        self::load($file->getPathName());
      }
      elseif ($file->isFile() && $file->getExtension() == 'php') {
        $name = $file->getBasename('.php');
        $filename = $file->getPathName();
        $replaceable = include $filename;
        self::set($name, $replaceable);
      }
    }
  }

  static function set($name, $replaceable)
  {
    # No replaceable with this name exists
    if (!function_exists($name)) {
      eval('function ' . $name . '() { return ' . __class__ . '::call("' . $name . '", func_get_args()); }');
    }
    return self::$replaceables[$name] = $replaceable;
  }

  static function call($name, $args, $callable = null)
  {
    if (isset(self::$replaceables[$name])) {
      $callable = self::$replaceables[$name];
    }
    elseif (!$callable) {
      throw new exception("Unknown replaceable ($name).", 500);
    }
    if (!$args) {
      return $callable();
    }
    switch (count($args)) {
      case 1: return $callable($args[0]);
      case 2: return $callable($args[0], $args[1]);
      case 3: return $callable($args[0], $args[1], $args[2]);
      case 4: return $callable($args[0], $args[1], $args[2], $args[3]);
      case 5: return $callable($args[0], $args[1], $args[2], $args[3], $args[4]);
    }
    return call_user_func_array($callable, $args);
  }

  static function __callStatic($name, $args)
  {
    return self::call($name, $args);
  }

}
