<?php

return function($value = null) {
  static $app_path = '';
  return isset($value) ? $app_path = $value : $app_path;
};
