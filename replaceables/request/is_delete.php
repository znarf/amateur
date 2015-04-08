<?php

namespace amateur
{

use amateur\core\amateur;

function is_delete()
{
  return amateur::request_method() == 'DELETE';
}

}
