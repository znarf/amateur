<?php

return function($code = 500, $message = 'Application Error', $trace = '') {
  response_code($code);
  # Try error views
  foreach ([$code, 'error'] as $view) {
    if ($result = view($view, compact('code', 'message', 'trace'))) break;
  }
  layout( isset($result) ? $result : "<h2>{$code} {$message}</h2>" . "<pre>{$trace}</pre>" );
};
