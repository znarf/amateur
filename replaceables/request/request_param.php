<?php namespace amateur;

function request_param($name, $value = null)
{
  if (isset($value)) {
    return $_REQUEST[$name] = $value;
  }
  elseif (isset($_REQUEST[$name])) {
    return $_REQUEST[$name];
  }
}
