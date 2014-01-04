<?php

return function($value = null) {
  static $content;
  if ($value) {
    $content = $value;
  }
  return $content;
};
