<?php

namespace Core;

class Response
{

  static function exception($e)
  {
    error($e->getCode(), $e->getMessage());
  }

}
