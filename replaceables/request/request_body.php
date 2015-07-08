<?php namespace amateur;

function request_body()
{
  return file_get_contents("php://input");
}
