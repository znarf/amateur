<?php

return function($name, $args = []) {
  # Registry
  static $views = [];
  # Store View (not an array and callable)
  if ($args !== (array)$args && is_callable($args)) {
    return $views[$name] = $args;
  }
  # Stored View
  if (isset($views[$name])) {
    ob_start();
    $views[$name]($args);
    return response_content(ob_get_clean());
  }
  # Default view
  return default_view($name, $args);
};
