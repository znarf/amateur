<?php

function status($code, $message)
{
  header("HTTP/1.1 $code $message");
}

function set_header($name, $value)
{
  header("$name:$value");
}

function redirect($url)
{
  set_header("Location", $url);
  exit;
}
