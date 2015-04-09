<?php namespace amateur;

function default_error($code = 500, $message = 'Application Error', $trace = '')
{
  $content = "<h2>{$message}</h2>";
  if ($trace) {
    $content .= "<pre>{$trace}</pre>";
  }
  return $content;
}
