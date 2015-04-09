<?php namespace amateur;

function not_found($message = 'Not Found')
{
  return amateur::error(404, $message);
}
