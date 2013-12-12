<?php

return function($name, $callable = null) {
  # Registry
  static $modules = [];
  # Set
  if (isset($callable) && is_callable($callable)) {
    return $modules[$name] = $callable;
  }
  # Loaded
  if (isset($modules[$name]) || array_key_exists($name, $modules)) {
    $module = $modules[$name];
    return $module();
  }
  # Load
  else {
    $module = include filename('module', $name);
    if (is_callable($module)) {
      $modules[$name] = $module;
      return $module();
    }
  }
};
