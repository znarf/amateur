<?php

return function($code = 500, $message = 'Application Error', $trace = '') {
  response_code($code);
  foreach ([$code, 'error'] as $view) {
    if ($content = view($view, compact('code', 'message', 'trace'))) break;
  }
  if (empty($content)) {
    $content = default_error($code, $message, $trace);
  }
  layout('error', $content);
  finish();
};
