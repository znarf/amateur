<?php

return function($name, $content = null) {
  # Include Layout
  if ($filename = filename('layout', $name)) {
    return include $filename;
  }
  # Default Default
  elseif ($name == 'none' || $name == 'default') {
    return response_content($content);
  }
  # Forward to Default
  else {
   return layout('default', $content);
  }
};
