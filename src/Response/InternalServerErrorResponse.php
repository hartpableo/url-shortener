<?php

namespace UrlShortener\Response;

final class InternalServerErrorResponse {
  public function __construct(
    public string $message = 'Internal Server Error',
    public bool $return = FALSE,
    private readonly int $status = 500
  ) {
  }

  public function __invoke() {
    http_response_code($this->status);

    if ($this->return) {
      return $this->message;
    }

    echo $this->message;
    exit;
  }
}