<?php

namespace amateur
{

function response_code($value = null)
{
  static $code;
  if ($value) {
    $code = $value;
  }
  return $code;
}

}
