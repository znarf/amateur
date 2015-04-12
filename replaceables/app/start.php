<?php namespace amateur;

function start($callable = null)
{
  # Load Replaceable
  $action = amateur::replaceable('action');
  # Store
  if ($callable) {
    $action('start', $callable);
  }
  # Execute and catch exceptions
  try {
    $action('start');
  }
  catch (\amateur\exception $e) {
    ob_end_clean();
    amateur::error($e->getCode(), $e->getMessage(), $e->getTraceAsString());
  }
  catch (\exception $e) {
    ob_end_clean();
    amateur::error(500, $e->getMessage(), $e->getTraceAsString());
  }
}
