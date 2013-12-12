<?php

return function($str) {
  return strpos(request_url(), $str) === 0;
};
