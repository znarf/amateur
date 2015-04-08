<?php

namespace amateur
{

function arg($string)
{
  return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
}

}