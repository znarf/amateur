<?php

return function($callable = null) {
  # Store
  if ($callable) {
    action('start', $callable);
  }
  # Execute and catch exceptions
  try {
    action('start');
  }
  catch (\amateur\core\exception $e) {
    ob_end_clean();
    error($e->getCode(), $e->getMessage(), $e->getTraceAsString());
  }
  catch (exception $e) {
    ob_end_clean();
    error(500, $e->getMessage(), $e->getTraceAsString());
  }
};
