<?php namespace amateur;

function view($name, $args = [])
{
  # Init Registry
  if (!isset(amateur::$registry['views'])) {
    amateur::$registry['views'] = [];
  }
  # Store View
  if (!empty($args) && is_callable($args)) {
    return amateur::$registry['views'][$name] = $args;
  }
  # Stored View (callable)
  if (isset(amateur::$registry['views'][$name])) {
    ob_start();
    amateur::$registry['views'][$name]($args);
    return amateur::response_content(ob_get_clean());
  }
  # Default view
  $default_view = replaceable::get('default_view', true);
  return $default_view($name, $args);
}
