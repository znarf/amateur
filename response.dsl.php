<?php

if (empty($response)) {
  require_once __DIR__ . '/response.class.php';
  $GLOBALS['response'] = $response = new \Core\Response;
}

foreach (['status', 'set_header', 'ok'] as $method) {
  replaceable($method, [$response, $method]);
}

replaceable('redirect', function($path) use($response) {
  $url = app_path() . $path;
  $response->set_header("Location", $url);
  exit;
});
