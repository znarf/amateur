<?php namespace amateur;

function response_header($name = null, $value = null)
{
  if ($name) {
    if ($value) {
      amateur::$registry['response_headers'][$name] = $value;
    }
    else {
      unset($registry['response_headers'][$name]);
    }
  }
  if (isset(amateur::$registry['response_headers'])) {
    return amateur::$registry['response_headers'];
  }
  else {
    return [];
  }
}
