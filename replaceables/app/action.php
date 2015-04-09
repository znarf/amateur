<?php namespace amateur;

function action($name, $callable = null)
{
  # Registry
  static $actions = [];
  # Store Action (not null and callable)
  if (isset($callable) && is_callable($callable)) {
    return $actions[$name] = $callable;
  }
  # Default Action
  if (empty($actions[$name])) {
    $action = $actions[$name] = amateur::default_action($name);
  }
  # Stored Action
  else {
    $action = $actions[$name];
  }
  # Execute Action
  if (is_callable($action)) {
    $action();
  }
}
