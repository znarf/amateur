<?php

return function() {
  return request_protocol() . '://' . request_host() . app_path() . request_url();
};
