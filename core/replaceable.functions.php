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
  if (empty($GLOBALS['replaceables'])) {
    $GLOBALS['replaceables'] = [];
  }
  if (empty($GLOBALS['replaceables'][$name])) {
    eval('function ' . $name . '() {
      global $replaceables;
      if (!is_callable($replaceables["' . $name . '"]))
        throw new Exception("Unknown replaceable (' . $name . ')");
      $callable = $replaceables["' . $name . '"];
      $args = func_get_args();
      switch (count($args)) {
        case 0: return $callable();
        case 1: return $callable($args[0]);
        case 2: return $callable($args[0], $args[1]);
        case 3: return $callable($args[0], $args[1], $args[2]);
        case 4: return $callable($args[0], $args[1], $args[2], $args[3]);
        case 5: return $callable($args[0], $args[1], $args[2], $args[3], $args[4]);
      }
      return call_user_func_array($callable, $args);
    }');
  }
  $GLOBALS['replaceables'][$name] = $replaceable;
  return $replaceable;
}
