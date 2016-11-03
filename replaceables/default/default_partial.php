<?php namespace amateur;

function default_partial($name, $args = [])
{
  if ($filename = amateur::filename('partial', $name)) {
    extract($args);
    $result = include $filename;
    return $result;
  }
  throw new exception("Unknown partial ($name).", 500);
}
