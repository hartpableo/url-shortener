<?php

namespace UrlShortener;

use UrlShortener\Response\HtmlResponse;
use UrlShortener\Response\JsonResponse;

class CreateShortUrl {
  public function index(): HtmlResponse {
    return new HtmlResponse('home');
  }

  public function create(): JsonResponse {
    sleep(25);
    return new JsonResponse([
      'ok' => TRUE,
      'data' => [

      ],
      'messages' => [
        [
          'type' => 'error',
          'message' => 'test error message'
        ],
        [
          'type' => 'success',
          'message' => 'test success message'
        ]
      ]
    ]);
  }
}