<?php

return function($value = null)
{
  static $url;
  if (isset($value)) {
    return $url = $value;
  }
  elseif (!isset($url)) {
    $url = str_replace(app_path(), '', strtok($_SERVER['REQUEST_URI'], '?'));
  }
  return $url;
};
