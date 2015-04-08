<?php

namespace amateur
{

use amateur\core\amateur;

function is_get()
{
  return amateur::request_method() == 'GET';
}

}
