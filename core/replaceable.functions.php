<?php

function instance($classname)
{
  return function() use($classname) {
    static $instance;
    return $instance ? $instance : $instance = new $classname;
  };
}

function replaceable($name, $replaceable)
{
  global $replaceables;
  if (!function_exists($name)) {
    eval('function ' . $name . '() { return replaceable_call("' . $name . '", func_get_args()); }');
  }
  return $replaceables[$name] = $replaceable;
}

function replaceable_call($name, $args = [], $callable = null)
{
  global $replaceables;
  if (isset($replaceables[$name])) {
    $callable = $replaceables[$name];
  }
  elseif (!$callable) {
    throw new exception("Unknown replaceable ($name).");
  }
  if (!$args) {
    return $callable();
  }
  switch (count($args)) {
    case 0: return $callable();
    case 1: return $callable($args[0]);
    case 2: return $callable($args[0], $args[1]);
    case 3: return $callable($args[0], $args[1], $args[2]);
    case 4: return $callable($args[0], $args[1], $args[2], $args[3]);
    case 5: return $callable($args[0], $args[1], $args[2], $args[3], $args[4]);
  }
  return call_user_func_array($callable, $args);
}
