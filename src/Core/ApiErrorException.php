<?php

namespace App\Core;


use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiErrorException extends HttpException
{
  public function __construct(ApiError $apiError, Exception $previous = null, array $headers = array(), $code = 0)
  {
    parent::__construct(
      $apiError->getStatusCode(),
      $apiError->getTitle(),
      $previous,
      $headers,
      $code
    );
  }
}
