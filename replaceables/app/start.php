<?php

return function() {
  try {
    $start = include app_dir() . '/app.start.php';
    if (is_callable($start)) $start();
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
