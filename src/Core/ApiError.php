<?php

namespace App\Core;

use InvalidArgumentException;

class ApiError
{
  const TYPE_VALIDATION_ERROR = 'validation_error';
  const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';
  const RESOURCE_NOT_FOUND = 'resource_not_found';
  const INVALID_DATETIME = 'invalid_datetime';
  const MISSING_PARAM = 'missing_params';
  const PAGE_NOT_FOUND = 'page_not_found';
  static private array $titles = [
    self::TYPE_VALIDATION_ERROR => 'There was a validation error',
    self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent',
    self::RESOURCE_NOT_FOUND => 'Resource not found',
    self::INVALID_DATETIME => 'Invalid date-time, expected format YYYY-MM-DD',
    self::MISSING_PARAM => 'Missing mandatory parameter',
    self::PAGE_NOT_FOUND => 'Page not found',
  ];
  private string $statusCode;
  private string $type;
  private string $title;

  public function __construct($statusCode, $type)
  {
    $this->statusCode = $statusCode;
    $this->type = $type;

    if (isset(self::$titles[$type])) {
      $this->title = self::$titles[$type];
    } elseif (!empty($type)) {
      $this->title = $type;
    } else {
      throw new InvalidArgumentException('No title for type '.$type);
    }

  }

  public function getStatusCode(): string
  {
    return $this->statusCode;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function toArray(): array
  {
    return [
      'status' => $this->statusCode,
      'type' => $this->type,
      'title' => $this->title,
    ];
  }

}
