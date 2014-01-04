<?php

return function() {
  if ($code = response_code()) {
    http_response_code($code);
  }
  foreach (response_header() as $name => $value) {
    header("$name:$value");
  }
  if ($content = response_content()) {
    echo $content;
  }
  if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
  }
  else {
    flush();
  }
  exit;
};
