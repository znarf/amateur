<?php

return function() {
  return in_array(request_method(), ['POST', 'PATCH', 'PUT', 'DELETE']);
};
