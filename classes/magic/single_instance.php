<?php namespace amateur\magic;

trait single_instance
{

  static function instance()
  {
    return \amateur\amateur::instance(__class__);
  }

}
