<?php

namespace amateur
{

use amateur\core\amateur;

function get_param($name, $default = null)
{
  $value = amateur::request_param($name);
  return isset($value) ? $value : $default;
}

}
