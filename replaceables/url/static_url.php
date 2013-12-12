<?php

return function($path = '') {
  return '//' . request_host() . app_path() . $path;
};
