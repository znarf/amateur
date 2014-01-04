<?php

return function($name, $callable = null) {
  # Registry
  static $modules = [];
  # Store Module (not null and callable)
  if (isset($callable) && is_callable($callable)) {
    return $modules[$name] = $callable;
  }
  # Stored Module
  if (isset($modules[$name])) {
    $module = $modules[$name];
    return $module();
  }
  # Execute Module ...
  # ... or return a callable to be stored and executed
  $module = default_module($name);
  if (is_callable($module)) {
    $modules[$name] = $module;
    return $module();
  }
};
