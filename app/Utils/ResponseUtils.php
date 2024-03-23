<?php

namespace App\Utils;

class ResponseUtils {
  static function build($code = 200, $message = "Success", $isError = false, $data = null) {
    return [
      'isError' => $isError,
      'code' => $code,
      'message' => $message,
      'data' => $data
    ];
  }
};