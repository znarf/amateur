<?php namespace amateur;

function http_error($code, $message)
{
  return new exception($message, $code);
}
