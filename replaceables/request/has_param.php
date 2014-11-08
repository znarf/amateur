<?php

return function($name) {
  return request_param($name) !== null;
};
