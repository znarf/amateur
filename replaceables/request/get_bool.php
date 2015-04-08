<?php

namespace amateur
{

use amateur\core\amateur;

function get_bool($name, $default = null)
{
  $value = amateur::request_param($name);
  if (!isset($value)) {
    return $default;
  }
  elseif (is_string($value) && strtolower($value) == 'true') {
    return true;
  }
  elseif (is_string($value) && strtolower($value) == 'false') {
    return false;
  }
  else {
    return (bool)$value;
  }
}

}
