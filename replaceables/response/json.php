<?php

return function($content) {
  response_header('Content-Type', 'application/json');
  response_content(json_encode($content));
  finish();
};
