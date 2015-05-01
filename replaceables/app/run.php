<?php namespace amateur;

function run($callable = null)
{
  # Catch Exceptions
  try {
    # Execute the callable passed as parameter
    if (is_callable($callable)) {
      $callable();
    }
    # Default with start action
    else {
      $action = replaceable::get('action', true);
      $action('start');
    }
  }
  catch (\amateur\exception $e) {
    ob_end_clean();
    amateur::error($e->getCode(), $e->getMessage(), $e->getTraceAsString());
  }
  catch (\exception $e) {
    ob_end_clean();
    amateur::error(500, $e->getMessage(), $e->getTraceAsString());
  }
  # Send the response & exit (if finish not already triggered)
  amateur::finish();
}
