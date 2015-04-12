<?php namespace amateur;

function app_dir($value = null)
{
  if (!isset(amateur::$registry['app_dir'])) {
    amateur::$registry['app_dir'] = './';
  }
  if ($value) {
    amateur::$registry['app_dir'] = realpath($value);
  }
  return amateur::$registry['app_dir'];
}
