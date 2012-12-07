<?php

class response
{

  static function exception($e)
  {
    error($e->getCode(), $e->getMessage());
  }

}
