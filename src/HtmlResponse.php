<?php

namespace UrlShortener;

class HtmlResponse implements ResponseInterface {
  public function __construct(
    public string $template,
    public array $data = [],
    public int $status = 200
  ) {
  }

  public function getResponse(): false|string {
    http_response_code($this->status);
    ob_start();
    include __DIR__ . "/../templates/{$this->template}.tpl.php";
    return ob_get_clean();
  }
}