<?php namespace amateur;

function default_module($name)
{
  if ($filename = amateur::filename('module', $name)) {
    return include $filename;
  }
  throw new exception("Unknown module ($name).", 500);
}
