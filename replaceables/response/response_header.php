<?php

return function($name = null, $value = null) {
  static $headers = [];
  if ($name) {
    if ($value) {
      $headers[$name] = $value;
    }
    else {
      unset($headers[$name]);
    }
  }
  return $headers;
};