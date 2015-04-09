<?php namespace amateur;

function app_dir($value = null)
{
  static $app_dir = './';
  return isset($value) ? $app_dir = realpath($value) : $app_dir;
}
