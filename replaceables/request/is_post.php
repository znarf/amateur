<?php

namespace amateur
{

use amateur\core\amateur;

function is_post()
{
  return amateur::request_method() == 'POST';
}

}
