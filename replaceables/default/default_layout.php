<?php namespace amateur;

function default_layout($name, $args = [])
{
  # Include Layout
  if ($filename = amateur::filename('layout', $name)) {
    extract($args);
    return include $filename;
  }
  # Default Default
  elseif ($name == 'none' || $name == 'default') {
    return amateur::response_content($args['content']);
  }
  # Not Found, forward to default/none
  else {
   return amateur::layout('default', $args);
  }
}
