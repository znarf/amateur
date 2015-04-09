<?php namespace amateur;

function app_path($value = null)
{
  static $app_path = '';
  return isset($value) ? $app_path = $value : $app_path;
}
