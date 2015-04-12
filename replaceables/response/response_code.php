<?php namespace amateur;

function response_code($value = null)
{
  if ($value) {
    amateur::$registry['response_code'] = $value;
  }
  if (isset(amateur::$registry['response_code'])) {
    return amateur::$registry['response_code'];
  }
}
