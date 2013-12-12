<?php

return function($name) {
  # Multi
  if ($name === (array)$name) {
    return array_map('helper', $name);
  }
  # Default
  $instance = function() use ($name) {
    return include filename('helper', $name);
  };
  # Registry
  return registry('helper', $name, $instance);
};
