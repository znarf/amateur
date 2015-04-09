<?php namespace amateur;

function absolute_url($path = '')
{
  return amateur::request_protocol() . '://' . amateur::request_host() . amateur::app_path() . $path;
}
