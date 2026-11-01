<?php

namespace UrlShortener;

use UrlShortener\Response\HtmlResponse;
use UrlShortener\Response\JsonResponse;

class CreateShortUrl {
  private Database $db;
  private Request $request;

  public function __construct() {
    $this->db = new Database();
    $this->request = new Request();
  }

  public function index(): HtmlResponse {
    return new HtmlResponse('home');
  }

  public function create(): JsonResponse {
    $originalUrl = $this->request->input('originalUrl');
    $shortVersion = $this->request->input('shortVersion');

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