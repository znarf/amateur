<?php

if (empty($response)) {
  require_once __DIR__ . '/response.class.php';
  $GLOBALS['response'] = $response = new \Amateur\Core\Response;
}

foreach (['status', 'set_header', 'redirect', 'ok'] as $method) {
  replaceable($method, [$response, $method]);
}
