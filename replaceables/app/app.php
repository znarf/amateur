<?php

namespace amateur
{

use amateur\core\amateur;

function app($start = null, $finish = null)
{
  amateur::start($start);
  amateur::finish($finish);
}

}
