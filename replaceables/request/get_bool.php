<?php

return function($name, $default = null) {
  $value = request_param($name);
  if (!isset($value)) {
    return $default;
  }
  elseif (is_string($value) && strtolower($value) == 'true') {
    return true;
  }
  elseif (is_string($value) && strtolower($value) == 'false') {
    return false;
  }
  else {
    return (bool)$value;
  }
};
