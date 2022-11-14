<?php

if (!function_exists('cms_response')) {
  function cms_response($msg, $success = true, $http_status = 200, $data = [])
  {
    return [
      'data' => $data,
      'http_status' => $http_status,
      'msg' => $msg,
      'success' => $success,
    ];
  }
}
