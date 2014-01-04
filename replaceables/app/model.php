<?php

return function($name) {
  # Multi
  if ($name === (array)$name) {
    return array_map('model', $name);
  }
  # Default
  $instance = function() use ($name) {
    return default_model($name);
  };
  # Registry
  return registry('model', $name, $instance);
};
