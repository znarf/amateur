<?php namespace amateur;

function get_int($name, $default = null)
{
  $value = amateur::request_param($name);
  return isset($value) ? (int)$value : $default;
}
