<?php namespace amateur;

function response_content($value = null)
{
  static $content;
  if ($value) {
    $content = $value;
  }
  return $content;
}
