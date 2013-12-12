<?php

return function($methods) {
  $methods = is_string($methods) ? explode(",", strtoupper($methods)) : $methods;
  if (!in_array(request_method(), $methods)) {
    throw http_error(405, 'Method Not Allowed');
  }
};
