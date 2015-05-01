<?php namespace amateur;

function helper($name, $helper = null)
{
  # Init Registry
  if (!isset(amateur::$registry['helpers'])) {
    amateur::$registry['helpers'] = [];
  }
  # Multi
  if ($name === (array)$name) {
    return array_map(__function__, $name);
  }
  # Store Helper (object or closure)
  if ($helper && is_object($helper)) {
    return amateur::$registry['helpers'][$name] = $helper;
  }
  # Loaded (even if it's null, to load only once)
  if (isset(amateur::$registry['helpers'][$name]) || array_key_exists($name, amateur::$registry['helpers'])) {
    $helper = amateur::$registry['helpers'][$name];
  }
  # Load
  else {
    $default_helper = replaceable::get('default_helper', true);
    $helper = amateur::$registry['helpers'][$name] = $default_helper($name);
  }
  # If it's a closure, execute and store
  if ($helper && $helper instanceof \closure) {
    $helper = amateur::$registry['helpers'][$name] = $helper();
  }
  # Return
  return $helper;
}
