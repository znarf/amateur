<?php namespace amateur;

function is_ajax()
{
  return amateur::request_header('X-Requested-With') == 'XMLHttpRequest';
}
