<?php

namespace amateur
{

use amateur\core\amateur;

function get_int($name, $default = null)
{
  $value = amateur::request_param($name);
  return isset($value) ? (int)$value : $default;
}

}
