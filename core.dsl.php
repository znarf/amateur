<?php

function core_dir($value = null)
{
  static $core_dir = null;
  return $value ? $core_dir = $value : __DIR__;
}

function core_load($name, $classname = null, $filename = null)
{
  if (empty($classname)) {
    $classname = ucfirst($name);
  }
  if (!class_exists($classname)) {
    if (empty($filename)) {
      $filename = core_dir() . '/' . $name . '.class.php';
    }
    require $filename;
  }
  if (class_exists($classname)) {
    $GLOBALS[$name] = new $classname();
  }
  include core_dir() . '/' . $name . '.dsl.php';
}

function replaceable_call($callable, $args)
{
  if (is_array($callable) || count($args) > 5) {
    return call_user_func_array($callable, $args);
  }
  switch (count($args)) { 
    case 0: return $callable();
    case 1: return $callable($args[0]);
    case 2: return $callable($args[0], $args[1]);
    case 3: return $callable($args[0], $args[1], $args[2]);
    case 4: return $callable($args[0], $args[1], $args[2], $args[3]);
    case 5: return $callable($args[0], $args[1], $args[2], $args[3], $args[4]);
  }
}

function replaceable($name, $replaceable)
{
  global $replaceables;
  isset($replaceables) || $replaceables = array();
  if (empty($replaceables[$name])) eval(
    'function ' . $name . '() {
      global $replaceables;
      if (is_callable($replaceables["' . $name . '"]))
      return replaceable_call($replaceables["' . $name . '"], func_get_args());
      throw new Exception("Unknown replaceable (' . $name . ')");
    }');
  $replaceables[$name] = $replaceable;
}
