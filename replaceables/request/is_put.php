<?php namespace amateur;

function is_put()
{
  return amateur::request_method() == 'PUT';
}
