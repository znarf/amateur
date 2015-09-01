<?php namespace amateur;

use DirectoryIterator as directory_iterator;

class replaceable
{

  static $index = [];

  static $replaceables = [];

  static $expose_global_functions = false;

  static function load($dir, $namespace = null)
  {
    foreach (new directory_iterator($dir) as $file) {
      if ($file->isFile() && $file->getExtension() == 'php') {
        $name = $file->getBasename('.php');
        $filename = $file->getPathName();
        self::$index[$name] = [$filename, $namespace];
      }
      elseif ($file->isDir() && !$file->isDot()) {
        self::load($file->getPathName(), $namespace);
      }
    }
  }

  static function load_replaceable($name, $filename, $namespace = null)
  {
    $replaceable = include $filename;
    if (!is_callable($replaceable)) {
       $replaceable = $namespace ? "\\{$namespace}\\{$name}" : $name;
    }
    return self::set($name, $replaceable);
  }

  static function get($name, $throw_exception = false)
  {
    # Multi
    if ($name === (array)$name) {
      return array_map([__class__, __method__], $name);
    }
    # Single
    if (isset(self::$replaceables[$name])) {
      return self::$replaceables[$name];
    }
    # Autoload (from index)
    if (isset(self::$index[$name])) {
      list($filename, $namespace) = self::$index[$name];
      return self::load_replaceable($name, $filename, $namespace);
    }
    # Exception
    if ($throw_exception) {
      throw new exception("Unknown replaceable ($name).", 500);
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
    $callable = replaceable::get($name, true);
    return $callable(...$args);
  }

  static function create_global_function($name)
  {
    if (!function_exists($name)) {
      eval('function ' . $name . '() {
        if (isset(' . __class__ . '::$replaceables["' . $name . '"])) {
          $callable = ' . __class__ . '::$replaceables["' . $name . '"];
        }
        else {
          $callable = ' . __class__ . '::get("' . $name . '", true);
        }
        return $callable(...func_get_args());
      }');
    }
  }

  static function expose_replaceables()
  {
    self::$expose_global_functions = true;
    $names = array_merge(array_keys(self::$replaceables), array_keys(self::$index));
    foreach ($names as $name) {
      self::create_global_function($name);
    }
  }

}
