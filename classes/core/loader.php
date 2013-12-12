<?php namespace amateur\core;

class loader
{

  static $paths = [];

  static $classes = [];

  static $autoload_registered;

  static $autostore_registered;

  static $classes_cache_key = 'amateur.loader.classes';

  static function register_namespace($namespace_prefix, $base_dir)
  {
    $namespace_prefix = trim($namespace_prefix, '\\') . '\\';
    $base_dir = rtrim($base_dir, '/') . '/';
    self::$paths[] = [$namespace_prefix, $base_dir];
    self::register_autoload();
  }

  static function register_autoload()
  {
    if (!self::$autoload_registered) {
      spl_autoload_register([__class__, 'autoload'], true, true);
      self::$autoload_registered = true;
    }
  }

  static function autoload($classname)
  {
    # First Run
    if (empty(self::$classes)) {
      if (function_exists('apc_fetch') && $classes = apc_fetch(self::$classes_cache_key)) {
        self::$classes = $classes;
      }
    }
    # Direct
    if (isset(self::$classes[$classname])) {
      $result = include self::$classes[$classname];
      if ($result) {
        return true;
      }
      else {
        unset(self::$classes[$classname]);
        self::register_autostore();
      }
    }
    # Remove leading \ if any
    $class = ltrim($classname, '\\');
    foreach (self::$paths as $path) {
      list($prefix, $base_dir) = $path;
      # does the class use the namespace prefix?
      $len = strlen($prefix);
      if (strncmp($prefix, $class, $len) !== 0) {
        # no, move to the next registered autoloader
        continue;
      }
      # get the relative class name
      $relative_class = substr($class, $len);
      # replace the namespace prefix with the base directory
      # replace namespace separators with directory separators in the relative class name
      # append with .php
      $filename = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
      # if the file exists, require it
      if (file_exists($filename)) {
        require $filename;
        self::$classes[$classname] = $filename;
        self::register_autostore();
        return true;
      }
    }
  }

  static function register_autostore()
  {
    if (!self::$autostore_registered) {
      register_shutdown_function([__class__, 'store']);
      self::$autostore_registered = true;
    }
  }

  static function store()
  {
    if (function_exists('apc_store')) {
      apc_store(self::$classes_cache_key, self::$classes);
    }
  }

}
