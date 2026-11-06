<?php

namespace UrlShortener\Response;

final class RedirectResponse implements ResponseInterface {
  public function __construct(
    public string $redirectTo,
    public int $status = 302
  ) {
  }

  public function getResponse() {
    http_response_code($this->status);
    header("Location: {$this->redirectTo}");
    return TRUE;
  }
}