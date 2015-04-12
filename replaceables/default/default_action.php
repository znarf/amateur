<?php namespace amateur;

function default_action($name, $args = [])
{
  if ($filename = amateur::filename('action', $name)) {
    extract($args);
    return include $filename;
  }
  throw new exception("Unknown action ($name).", 500);
}
