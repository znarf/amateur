<?php namespace amateur;

function start($callable = null)
{
  # Store
  if ($callable) {
    amateur::action('start', $callable);
  }
  # Execute and catch exceptions
  try {
    amateur::action('start');
  }
  catch (\amateur\core\exception $e) {
    ob_end_clean();
    amateur::error($e->getCode(), $e->getMessage(), $e->getTraceAsString());
  }
  catch (\exception $e) {
    ob_end_clean();
    amateur::error(500, $e->getMessage(), $e->getTraceAsString());
  }
}
