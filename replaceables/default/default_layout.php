<?php

return function($name, $args = []) {
  # Include Layout
  if ($filename = filename('layout', $name)) {
    extract($args);
    return include $filename;
  }
  # Default Default
  elseif ($name == 'none' || $name == 'default') {
    return response_content($args['content']);
  }
  # Forward to Default
  else {
   return layout('default', $args);
  }
};
