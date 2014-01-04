<?php

return function($content) {
  response_header('Content-Type', 'text/html');
  response_content($content);
  finish();
};
