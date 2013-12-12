<?php namespace amateur\http;

class response
{

  public $status_code;

  public $body;

  function __construct($status_code, $body = null)
  {
    $this->status_code = $status_code;
    $this->body = $body;

  }

}
