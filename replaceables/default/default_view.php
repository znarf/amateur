<?php

return function($name, $args = []) {
  if ($filename = filename('view', $name)) {
    ob_start();
    extract($args);
    include $filename;
    return response_content(ob_get_clean());
  }
  throw http_error(500, "Unknown view ($name).");
};
