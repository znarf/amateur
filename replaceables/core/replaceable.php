<?php

return function($name, $replaceable = null) {
  # Set
  if ($replaceable) {
    return \amateur\core\replaceable::set($name, $replaceable);
  }
  # Get
  return \amateur\core\replaceable::get($name);
};
