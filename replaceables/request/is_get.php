<?php namespace amateur;

function is_get()
{
  return amateur::request_method() == 'GET';
}
