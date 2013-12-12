<?php

return function($path, $permanent = false) {
  $url = strpos($path, '://') !== false ? $path : app_path() . $path;
  response_code($permanent ? 301 : 302);
  response_header('Location', $url);
  finish();
};
