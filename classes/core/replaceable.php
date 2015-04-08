<?php namespace amateur\core;

class replaceable
{

  static $replaceables = [];

  static function instance()
  {
    return registry::instance('core', 'replaceable', __class__);
  }

  public function load($dir, $namespace = null)
  {
    foreach (new \DirectoryIterator($dir) as $file) {
      if ($file->isDir() && !$file->isDot()) {
        self::load($file->getPathName(), $namespace);
      }
      elseif ($file->isFile() && $file->getExtension() == 'php') {
        $name = $file->getBasename('.php');
        $replaceable = include $file->getPathName();
        if (is_callable($replaceable)) {
          self::set($name, $replaceable);
        }
        else {
          self::set($name, $namespace ? "\\{$namespace}\\{$name}" : $name);
        }
      }
    }
  }

  static function get($name)
  {
    if ($name === (array)$name) {
      return array_map(['self', 'get'], $name);
    }
    if (isset(self::$replaceables[$name])) {
      return self::$replaceables[$name];
    }
  }

  static function set($name, $replaceable)
  {
    # No replaceable with this name exists
    if (empty(self::$replaceables[$name])) {
      if (!function_exists($name)) {
        eval('function ' . $name . '() { return ' . __class__ . '::call("' . $name . '", func_get_args()); }');
      }
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
      return is_callable($callable) ? $callable() : $callable;
    }
    switch (count($args)) {
      case 1: return $callable($args[0]);
      case 2: return $callable($args[0], $args[1]);
      case 3: return $callable($args[0], $args[1], $args[2]);
    }
    return call_user_func_array($callable, $args);
  }

}
