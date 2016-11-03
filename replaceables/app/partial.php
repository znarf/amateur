<?php namespace amateur;

function partial($name, $args = [])
{
  # Init Registry
  if (!isset(amateur::$registry['partials'])) {
    amateur::$registry['partials'] = [];
  }
  # Store Partial (callable expected)
  if (!empty($args) && is_callable($args)) {
    return amateur::$registry['partials'][$name] = $args;
  }
  # Stored Partial
  if (isset(amateur::$registry['partials'][$name])) {
    $partial = amateur::$registry['partials'][$name];
    return $partial($args);
  }
  # Default Partial
  $default_partial = amateur::replaceable('default_partial');
  $result = $default_partial($name, $args);
  # If a callable is returned
  if (is_callable($result)) {
    # Store It
    $partial = amateur::$registry['partials'][$name] = $result;
    # Execute it immediately
    $result = $partial($args);
  }
  # Return result
  return $result;
}
