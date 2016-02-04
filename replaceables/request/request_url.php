<?php namespace amateur;

function request_url($value = null)
{
  if (isset($value)) {
    return amateur::$registry['request_url'] = $value;
  }
  if (!isset(amateur::$registry['request_url'])) {
    $request_uri = strtok($_SERVER['REQUEST_URI'], '?');
    $app_path_pattern = '/' . str_replace('/', '\/', amateur::app_path()) . '/';
    $request_uri = preg_replace($app_path_pattern, '', $request_uri, 1);
    amateur::$registry['request_url'] = $request_uri;
  }
  return amateur::$registry['request_url'];
}
