<?php namespace amateur;

function request_header($name)
{
  # Init Registry
  if (!isset(amateur::$registry['request_headers'])) {
    amateur::$registry['request_headers'] = [];
  }
  if (array_key_exists($name, amateur::$registry['request_headers'])) {
    return amateur::$registry['request_headers'][$name];
  }
  else {
    $key = 'HTTP_' . str_replace('-', '_', strtoupper($name));
    return amateur::$registry['request_headers'][$name] = isset($_SERVER[$key]) ? $_SERVER[$key] : null;
  }
}
