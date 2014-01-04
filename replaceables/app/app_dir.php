<?php

return function($value = null) {
  static $app_dir = './';
  return isset($value) ? $app_dir = realpath($value) : $app_dir;
};
