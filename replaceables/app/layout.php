<?php namespace amateur;

function layout($name, $args = [])
{
  # Init Registry
  if (!isset(amateur::$registry['layouts'])) {
    amateur::$registry['layouts'] = [];
  }
  # Store Layout
  if (!empty($args) && is_callable($args)) {
    return amateur::$registry['layouts'][$name] = $args;
  }
  # If string is passed as argument, use it as content
  if (is_string($args)) {
    $args = ['content' => $args];
  }
  # If no content is passed as argument, use current response_content
  if (empty($args['content'])) {
    $args['content'] = amateur::response_content();
  }
  # Start output buffering
  ob_start();
  # Stored Layout (callable)
  if (isset(amateur::$registry['layouts'][$name])) {
    amateur::$registry['layouts'][$name]($args);
  }
  # Default Layout
  else {
    $default_layout = replaceable::get('default_layout', true);
    $default_layout($name, $args);
  }
  # Set content from output buffer
  return amateur::response_content(ob_get_clean());
}
