<?php

return function($name) {
  return (bool) request_param($name);
};
