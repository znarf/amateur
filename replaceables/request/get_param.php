<?php namespace amateur;

function get_param($name, $default = null)
{
  $value = amateur::request_param($name);
  return isset($value) ? $value : $default;
}
