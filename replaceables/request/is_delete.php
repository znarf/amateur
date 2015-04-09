<?php namespace amateur;

function is_delete()
{
  return amateur::request_method() == 'DELETE';
}
