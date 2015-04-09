<?php namespace amateur;

function model($name)
{
  # Multi
  if ($name === (array)$name) {
    return array_map(__function__, $name);
  }
  # Default
  $instance = function() use ($name) {
    return amateur::default_model($name);
  };
  # Registry
  return amateur::registry('model', $name, $instance);
}
