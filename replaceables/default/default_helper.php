<?php namespace amateur;

function default_helper($name)
{
  if ($filename = amateur::filename('helper', $name)) {
    return include $filename;
  }
  throw new exception("Unknown helper ($name).", 500);
}
