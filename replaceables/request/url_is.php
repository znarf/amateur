<?php

return function($str) {
  return $str == request_url();
};
