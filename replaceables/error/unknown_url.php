<?php

namespace amateur
{

use amateur\core\amateur;

function unknown_url()
{
  return amateur::error(404, sprintf("No url match '%s'.", amateur::request_url()));
}

}
