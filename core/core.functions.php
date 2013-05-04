<?php

function core_require($filename)
{
  $filename = realpath($filename);
  return include $filename;
}

function core_object($name)
{
  if (empty($GLOBALS[$name])) {
    $classname = '\Amateur\Core\\' . ucfirst($name);
    if (!class_exists($classname, false)) {
      $filename = amateur_dir . '/core/' . $name . '.class.php';
      require_once $filename;
    }
    $GLOBALS[$name] = new $classname;
  }
  return $GLOBALS[$name];
}

class HttpException extends Exception {}

function http_error($code, $message)
{
  return new HttpException($message, $code);
}
