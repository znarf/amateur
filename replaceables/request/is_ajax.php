<?php

return function() {
  return request_header('X-Requested-With') == 'XMLHttpRequest';
};
