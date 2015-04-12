<?php namespace amateur;

function render($name, $args = [], $layout = 'default')
{
  amateur::view($name, $args);
  amateur::layout($layout);
  amateur::finish();
}
