<?php

return function($name, $args = []) {
  # Registry
  static $views = [];
  # Set view
  if (is_callable($args)) {
    return $views[$name] = $args;
  }
  # Function view
  if (isset($views[$name]) || array_key_exists($name, $views)) {
    ob_start();
    $views[$name]($args);
    return ob_get_clean();
  }
  # Include view
  $template = filename('view', $name);
  if (file_exists($template)) {
    ob_start();
    extract($args);
    include $template;
    return ob_get_clean();
  }
};
