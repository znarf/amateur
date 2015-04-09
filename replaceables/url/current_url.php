<?php namespace amateur;

function current_url()
{
  return amateur::absolute_url(amateur::request_url());
}
