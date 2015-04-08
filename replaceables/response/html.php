<?php

namespace amateur
{

use amateur\core\amateur;

function html($content)
{
  amateur::response_header('Content-Type', 'text/html');
  amateur::response_content($content);
  amateur::finish();
}

}
