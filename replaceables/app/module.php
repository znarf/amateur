<?php namespace amateur;

function module($name, $callable = null)
{
  # Init Registry
  if (!isset(amateur::$registry['modules'])) {
    amateur::$registry['modules'] = [];
  }
  # Store Module (not null and callable)
  if (isset($callable) && is_callable($callable)) {
    return amateur::$registry['modules'][$name] = $callable;
  }
  # Stored Module
  if (isset(amateur::$registry['modules'][$name])) {
    $module = amateur::$registry['modules'][$name];
    return $module();
  }
  # Execute Module ...
  # ... or return a callable to be stored and executed
  $default_module = amateur::replaceable('default_module');
  $result = $default_module($name);
  if (is_callable($result)) {
    $module = amateur::$registry['modules'][$name] = $result;
    $result = $module();
  }
  # Return result
  return $result;
}
