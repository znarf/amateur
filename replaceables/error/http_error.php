<?php

namespace amateur
{

use amateur\core\exception;

function http_error($code, $message)
{
  return new exception($message, $code);
}

}
