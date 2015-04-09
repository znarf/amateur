<?php namespace amateur;

function replaceable($name, $replaceable = null)
{
  # Set
  if ($replaceable) {
    return replaceable::set($name, $replaceable);
  }
  # Get
  return replaceable::get($name);
}
