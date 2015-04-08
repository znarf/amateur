<?php

namespace amateur
{

use amateur\core\amateur;

function is_put()
{
  return amateur::request_method() == 'PUT';
}

}
