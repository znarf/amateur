<?php namespace amateur;

function response_content($value = null)
{
  if ($value) {
    amateur::$registry['response_content'] = $value;
  }
  if (isset(amateur::$registry['response_content'])) {
    return amateur::$registry['response_content'];
  }
}
