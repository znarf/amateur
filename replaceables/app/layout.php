<?php

namespace amateur
{

use amateur\core\amateur;

function layout($name, $args = [])
{
  # Registry
  static $layouts = [];
  # Transition
  if (is_string($args)) {
    $args = ['content' => $args];
  }
  # Store Layout (not an array and callable)
  if ($args !== (array)$args && is_callable($args)) {
    return $layouts[$name] = $args;
  }
  # If no content is defined, use current response_content
  if (empty($args['content'])) {
    $args['content'] = amateur::response_content();
  }
  # Start output buffering
  ob_start();
  # Use stored Layout
  if (isset($layouts[$name])) {
    $layouts[$name]($args);
  }
  # Use default Layout
  else {
    amateur::default_layout($name, $args);
  }
  # Set content from output buffer
  return amateur::response_content(ob_get_clean());
}

}
