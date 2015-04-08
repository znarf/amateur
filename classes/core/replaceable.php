<?php namespace amateur\core;

class replaceable
{

  static $replaceables = [];

  static $expose_global_functions = false;

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
    if (self::$expose_global_functions) {
      self::create_global_function($name);
    }
    return self::$replaceables[$name] = $replaceable;
  }

  static function call($name, $args)
  {
    $callable = replaceable::get($name);
    if (!$callable) {
      throw new exception("Unknown replaceable ($name).", 500);
    }
    return $callable(...$args);
  }

  public function create_global_function($name)
  {
    if (!function_exists($name)) {
      eval('function ' . $name . '() { return ' . __class__ . '::call("' . $name . '", func_get_args()); }');
    }
  }

  public function expose_global_functions()
  {
    self::$expose_global_functions = true;
    foreach (self::$replaceables as $name => $replaceable) {
      self::create_global_function($name);
    }
  }

}
