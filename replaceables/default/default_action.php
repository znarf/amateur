<?php namespace amateur;

function default_action($name)
{
  if ($filename = amateur::filename('action', $name)) {
    return include $filename;
  }
}
