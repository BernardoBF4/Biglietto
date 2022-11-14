<?php

if (!function_exists('cms_response')) {
  function cms_response($msg, $data = [], $status = null, $http_status = null)
  {
    return [
      'data' => $data,
      'http_status' => $http_status,
      'msg' => $msg,
      'status' => $status,
    ];
  }
}
