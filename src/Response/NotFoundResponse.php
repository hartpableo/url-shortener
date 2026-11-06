<?php

namespace UrlShortener\Response;

final class NotFoundResponse {
  public function __construct(
    public string $message = 'Not Found',
    public bool $return = FALSE,
    private readonly int $status = 404
  ) {
  }

  public function __invoke() {
    $finalMessage = \sprintf(
      <<<HTML
          <h1>%s</h1>
          <p>URL requested: <strong>%s</strong></p>
        HTML,
      $this->message,
      request()->full
    );

    http_response_code($this->status);

    if ($this->return) {
      return $finalMessage;
    }

    echo $finalMessage;
    exit;
  }
}