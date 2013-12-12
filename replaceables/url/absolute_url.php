<?php

return function($path = '') {
  return request_protocol() . '://' . request_host() . app_path() . $path;
};
