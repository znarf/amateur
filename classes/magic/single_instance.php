<?php namespace amateur\magic;

trait single_instance
{

  static function single_instance()
  {
    return \amateur\registry::instance('instance', __class__, __class__);
  }

}
