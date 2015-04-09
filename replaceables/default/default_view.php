<?php namespace amateur;

function default_view($name, $args = [])
{
  if ($filename = amateur::filename('view', $name)) {
    ob_start();
    extract($args);
    include $filename;
    return amateur::response_content(ob_get_clean());
  }
  throw new exception("Unknown view ($name).", 500);
}
