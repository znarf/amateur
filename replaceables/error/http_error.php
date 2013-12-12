<?php

return function($code, $message) {
  return new \amateur\core\exception($message, $code);
};
