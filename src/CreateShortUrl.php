<?php

namespace UrlShortener;

use UrlShortener\Response\HtmlResponse;
use UrlShortener\Response\JsonResponse;

class CreateShortUrl {
  public function index(): HtmlResponse {
    return new HtmlResponse('home');
  }

  public function create(): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'data' => [
        'message' => 'Hello from ' . __CLASS__
      ]
    ]);
  }
}