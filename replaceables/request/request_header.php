<?php

namespace amateur
{

function request_header($name)
{
  static $headers = [];
  if (array_key_exists($name, $headers)) {
    return $headers[$name];
  }
  else {
    $key = 'HTTP_' . str_replace('-', '_', strtoupper($name));
    return $headers[$name] = isset($_SERVER[$key]) ? $_SERVER[$key] : null;
  }
}

}
