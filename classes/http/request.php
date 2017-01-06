<?php namespace amateur\http;

class request
{

  public $user_agent = 'Amateur Request';

  public $timeout = 2;

  public $method = 'GET';

  public $url;

  public $headers = [];

  public $params = [];

  function get($url, $params = null)
  {
    $this->method = 'GET';
    $this->url = $url;
    if (isset($params)) {
      $this->params = $params;
    }
    return $this->execute();
  }

  function put($url, $params = null)
  {
    $this->method = 'PUT';
    $this->url = $url;
    if (isset($params)) {
      $this->params = $params;
    }
    return $this->execute();
  }

  function post_json($url, $params = null)
  {
    $this->method = 'POST';
    $this->url = $url;
    if (isset($params)) {
      $this->params = json_encode($params);
    }
    $this->headers[] = 'Content-Type:application/json';
    return $this->execute();
  }

  function put_json($url, $params = null)
  {
    $this->method = 'PUT';
    $this->url = $url;
    if (isset($params)) {
      $this->params = json_encode($params);
    }
    $this->headers[] = 'Content-Type:application/json';
    return $this->execute();
  }

  function delete($url, $params = null)
  {
    $this->method = 'DELETE';
    $this->url = $url;
    if (isset($params)) {
      $this->params = $params;
    }
    return $this->execute();
  }

  function post($url, $params = null)
  {
    $this->method = 'POST';
    $this->url = $url;
    if (isset($params)) {
      $this->params = $params;
    }
    return $this->execute();
  }

  function execute()
  {
    $method  = $this->method;
    $url     = $this->url;
    $params  = $this->params;
    $headers = $this->headers;

    $ch = curl_init();

    # Method
    if ($method == 'POST') {
      curl_setopt($ch, CURLOPT_POST, true);
    } elseif ($method != 'GET') {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    }

    # Parameters
    if (!empty($params)) {
      # error_log($params);
      switch ($method) {
        case 'GET':
          $url .= (strpos($url, '?') === false ? '?': '&') . http_build_query($params, null, '&');
          break;
        case 'PUT':
        case 'DELETE':
          if (is_array($params)) {
            $params = http_build_query($params, null, '&');
          }
        case 'POST':
        default:
          curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
          break;
      }
    } else {
      # Fix 411 HTTP errors
      if ($method != 'GET') {
        $headers[] = "Content-Length:0";
      }
    }

    $time_start = microtime(true);
    // error_log("Request:$method:$url start");

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent . ' (curl)');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $time_end = microtime(true);
    $time = round($time_end - $time_start, 3);
    // error_log("Request:$method:$url completed in $time");

    return new response($code, $body);
  }

}
