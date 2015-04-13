<?php namespace amateur\magic;

trait single_instance
{

  static function instance()
  {
    return \amateur\registry::instance(__class__);
  }

}
