<?php

return function($code = 500, $message = 'Application Error', $trace = '') {
  $content = "<h2>{$code} {$message}</h2>";
  if ($trace) {
    $content .= "<pre>{$trace}</pre>";
  }
  return $content;
};
