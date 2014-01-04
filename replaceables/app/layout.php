<?php

return function($name, $content = null) {
  # Registry
  static $layouts = [];
  # Store Layout (not a string and callable)
  if ($content !== (string)$content && is_callable($content)) {
    return $layouts[$name] = $content;
  }
  # If no content is defined, use current response_content
  if (empty($content)) {
    $content = response_content();
  }
  # Start output buffering
  ob_start();
  # Use stored Layout
  if (isset($layouts[$name])) {
    $layouts[$name]($content);
  }
  # Use default Layout
  else {
    default_layout($name, $content);
  }
  # Set content from output buffer
  return response_content(ob_get_clean());
};
