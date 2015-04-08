<?php

namespace amateur
{

use amateur\core\amateur;

function json($content)
{
  amateur::response_header('Content-Type', 'application/json');
  amateur::response_content(json_encode($content));
  amateur::finish();
}

}
