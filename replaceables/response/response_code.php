<?php

return function($value = null) {
  static $code;
  if ($value) {
    $code = $value;
  }
  return $code;
};
