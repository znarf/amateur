<?php namespace amateur;

function redirect($path, $permanent = false)
{
  $url = strpos($path, '://') !== false ? $path : amateur::app_path() . $path;
  amateur::response_code($permanent ? 301 : 302);
  amateur::response_header('Location', $url);
  amateur::finish();
}
