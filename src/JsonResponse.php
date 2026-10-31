<?php

namespace UrlShortener;

final class JsonResponse implements ResponseInterface {
  public function __construct(
    public array $data = [],
    public int $status = 200
  ) {
  }

  public function getResponse(): string {
    http_response_code($this->status);
    header('Content-Type: application/json');
    return json_encode($this->data);
  }
}