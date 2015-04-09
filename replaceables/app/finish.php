<?php namespace amateur;

function finish()
{
  if ($code = amateur::response_code()) {
    http_response_code($code);
  }
  foreach (amateur::response_header() as $name => $value) {
    header("$name:$value");
  }
  if ($content = amateur::response_content()) {
    echo $content;
  }
  if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
  }
  else {
    flush();
  }
  exit;
}
