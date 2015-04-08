<?php

namespace amateur
{

use exception;

use amateur\core\amateur;

function error($code = 500, $message = 'Application Error', $trace = '')
{
  amateur::response_code($code);
  foreach ([$code, 'error'] as $view) {
    try {
      if ($content = amateur::view($view, compact('code', 'message', 'trace'))) break;
    }
    catch (exception $e) {
    }
  }
  if (empty($content)) {
    $content = amateur::default_error($code, $message, $trace);
  }
  amateur::layout('error', $content);
  amateur::finish();
}

}
