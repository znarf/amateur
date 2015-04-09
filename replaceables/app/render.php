<?php namespace amateur;

function render($name, $args = [], $layout = 'default')
{
  amateur::layout($layout, amateur::view($name, $args));
  amateur::finish();
}
