<?php namespace amateur;

function app_path($value = null)
{
  if (!isset(amateur::$registry['app_path'])) {
    amateur::$registry['app_path'] = '';
  }
  if ($value) {
    amateur::$registry['app_path'] = $value;
  }
  return amateur::$registry['app_path'];
}
