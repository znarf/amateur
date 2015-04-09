<?php namespace amateur;

function helper($name, $helper = null)
{
  # Registry
  static $helpers = [];
  # Multi
  if ($name === (array)$name) {
    return array_map(__function__, $name);
  }
  # Set helper (closure or object expected)
  if ($helper && is_object($helper)) {
    return $helpers[$name] = $helper;
  }
  # Loaded (even if it's null)
  if (isset($helpers[$name]) || array_key_exists($name, $helpers)) {
    $helper = $helpers[$name];
  }
  # Load
  else {
    $helper = amateur::default_helper($name);
    # If an object or a closure is returned
    if ($helper && is_object($helper)) {
      $helpers[$name] = $helper;
    }
    # Or if it has been registered in the $helpers array (even if it's null)
    elseif (isset($helpers[$name]) || array_key_exists($name, $helpers)) {
      $helper = $helpers[$name];
    }
  }
  # Execute closure (lazy loading)
  if ($helper && $helper instanceof closure) {
    $helper = $helpers[$name] = $helper();
  }
  # Return
  return $helper;
}
