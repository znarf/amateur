<?php namespace amateur;

function replaceable($name, $replaceable = null)
{
  return $replaceable ? replaceable::set($name, $replaceable) : replaceable::get($name);
}
