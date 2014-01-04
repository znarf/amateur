<?php

return function($content) {
  response_code(200);
  response_content($content);
  finish();
};
