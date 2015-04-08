<?php

namespace amateur
{

use amateur\core\amateur;

function view($name, $args = [])
{
  # Registry
  static $views = [];
  # Store View (not an array and callable)
  if ($args !== (array)$args && is_callable($args)) {
    return $views[$name] = $args;
  }
  # Stored View (callable)
  if (isset($views[$name])) {
    ob_start();
    $views[$name]($args);
    return amateur::response_content(ob_get_clean());
  }
  # Default view
  return amateur::default_view($name, $args);
}

}
