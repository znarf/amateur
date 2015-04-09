<?php namespace amateur;

function is_post()
{
  return amateur::request_method() == 'POST';
}
