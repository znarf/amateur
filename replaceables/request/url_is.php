<?php namespace amateur;

function url_is($str)
{
  return $str == amateur::request_url();
}

