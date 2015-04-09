<?php namespace amateur;

function text($string)
{
  return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
}
