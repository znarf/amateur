<?php

return function($name) {
  # Multi
  if ($name === (array)$name) {
    return array_map('model', $name);
  }
  # Default
  $instance = function() use ($name) {
    return include filename('model', $name);
  };
  # Registry
  return registry('model', $name, $instance);
};
