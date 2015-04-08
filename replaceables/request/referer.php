<?php

namespace amateur
{

use amateur\core\amateur;

function referer()
{
  return (string)amateur::request_header('Referer');
}

}
