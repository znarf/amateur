<?php namespace amateur;

function app_path($value = null)
{
  if (!isset(amateur::$registry['app_path'])) {
    amateur::$registry['app_path'] = '';
  }
  if ($value) {
    amateur::$registry['app_path'] = $value;
    unset(amateur::$registry['request_url']);
  }
  return amateur::$registry['app_path'];
}
