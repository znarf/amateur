<?php

return function($name, $default = null) {
  $value = request_param($name);
  return isset($value) ? (int)$value : $default;
};