<?php

return function($message = 'Not Found') {
  return error(404, $message);
};
