<?php

return function($code = 500, $message = 'Application Error', $trace = '') {
  response_code($code);
  foreach ([$code, 'error'] as $view) {
    try {
      if ($content = view($view, compact('code', 'message', 'trace'))) break;
    }
    catch (exception $e) {
    }
  }
  if (empty($content)) {
    $content = default_error($code, $message, $trace);
  }
  layout('error', $content);
  finish();
};
