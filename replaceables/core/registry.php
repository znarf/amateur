<?php namespace amateur;

function registry($type, $name, $instance = null)
{
  return registry::instance($type, $name, $instance);
}
