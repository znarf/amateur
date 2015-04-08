<?php

namespace amateur
{

use amateur\core\amateur;

function ok($content)
{
  amateur::response_code(200);
  amateur::response_content($content);
  amateur::finish();
}

}
