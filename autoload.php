<?php

defined('amateur_dir') || define('amateur_dir', __dir__);

spl_autoload_register(function($classname) {
  $classname = ltrim($classname, '\\');
  if (strpos($classname, 'amateur\\') === 0) {
    $dir = dirname(amateur_dir) . DIRECTORY_SEPARATOR;
    $filename = $dir . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.class.php';
    if (file_exists($filename)) {
      require $filename;
    }
  }
});
