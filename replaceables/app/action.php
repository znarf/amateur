<?php namespace amateur;

function action($name, $args = [])
{
  # Init Registry
  if (!isset(amateur::$registry['actions'])) {
    amateur::$registry['actions'] = [];
  }
  # Store Action (callable)
  if (!empty($args) && is_callable($args)) {
    return amateur::$registry['actions'][$name] = $args;
  }
  # Stored Action
  if (isset(amateur::$registry['actions'][$name])) {
    $action = amateur::$registry['actions'][$name];
    $result = $action($args);
  }
  # Default Action
  else {
    $default_action = amateur::replaceable('default_action');
    $result = $default_action($name, $args);
  }
  # If a callable is returned
  if (is_callable($result)) {
    # Store It
    $action = amateur::$registry['actions'][$name] = $result;
    # Execute it immediately
    $result = $action($args);
  }
  # Return result
  return $result;
}
