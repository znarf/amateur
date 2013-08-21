<?php

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
  isset($replaceables) || $replaceables = [];
  if (empty($replaceables[$name])) eval(
    'function ' . $name . '() {
      global $replaceables;
      if (is_callable($replaceables["' . $name . '"]))
      return call_user_func_array($replaceables["' . $name . '"], func_get_args());
      throw new Exception("Unknown replaceable (' . $name . ')");
    }');
  $replaceables[$name] = $replaceable;
  return $replaceable;
}