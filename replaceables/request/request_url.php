<?php

namespace amateur
{

use amateur\core\amateur;

function request_url($value = null)
{
  static $url;
  if (isset($value)) {
    return $url = $value;
  }
  elseif (!isset($url)) {
    $request_uri = strtok($_SERVER['REQUEST_URI'], '?');
    $url = str_replace(amateur::app_path(), '', $request_uri);
  }
  return $url;
}

}
