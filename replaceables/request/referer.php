<?php namespace amateur;

function referer()
{
  return (string)amateur::request_header('Referer');
}
