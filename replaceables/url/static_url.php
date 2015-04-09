<?php namespace amateur;

function static_url($path = '')
{
  return '//' . amateur::request_host() . amateur::app_path() . $path;
}
