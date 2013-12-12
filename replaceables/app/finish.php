<?php

return function() {
  if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
  }
  else {
    flush();
  }
  exit;
};
