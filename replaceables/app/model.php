<?php

namespace amateur
{

use amateur\core\amateur;

function model($name)
{
  # Multi
  if ($name === (array)$name) {
    return array_map('\amateur\model', $name);
  }
  # Default
  $instance = function() use ($name) {
    return amateur::default_model($name);
  };
  # Registry
  return amateur::registry('model', $name, $instance);
}

}
