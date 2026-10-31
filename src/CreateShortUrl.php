<?php

namespace UrlShortener;

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