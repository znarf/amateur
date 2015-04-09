<?php namespace amateur;

function relative_url($path = '')
{
  return amateur::app_path() . $path;
}
