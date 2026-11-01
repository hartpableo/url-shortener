<?php

namespace UrlShortener\Controller;

use UrlShortener\Entity\ShortUrl;
use UrlShortener\Repository\ShortUrlRepository;
use UrlShortener\Response\HtmlResponse;
use UrlShortener\Response\JsonResponse;

class ShortUrlController {
  private ShortUrlRepository $shortUrlRepository;

  public function __construct() {
    $this->shortUrlRepository = new ShortUrlRepository();
  }

  public function index(): HtmlResponse {
    return new HtmlResponse('home');
  }

  public function getAll(): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'data' => $this->shortUrlRepository->findAll()
    ]);
  }

  public function create(): JsonResponse {
    $originalUrl = request()->input('originalUrl');
    $shortVersion = request()->input('shortVersion');
    $errors = [];

    // Check required fields
    if (empty($originalUrl)) {
      $errors[] = 'The originalUrl field is required.';
    }

    if (empty($shortVersion)) {
      $errors[] = 'The shortVersion field is required.';
    }

    if (!empty($errors)) {
      return new JsonResponse([
        'ok' => FALSE,
        'messages' => array_map(
          fn($err) => ['type' => 'error', 'message' => $err],
          $errors
        ),
      ]);
    }

    // Sanitize
    $originalUrl = urlencode($originalUrl);
    $shortVersion = urlencode($shortVersion);

    // Bail if already exists
    $existing = $this->shortUrlRepository->checkIfExists([
      'short_version' => $shortVersion
    ]);
    if ($existing) {
      return new JsonResponse([
        'ok' => TRUE,
        'messages' => [
          [
            'type' => 'info',
            'message' => 'Already exists'
          ]
        ],
        'data' => [
          'url' => $this->shortUrlRepository->findBy([
            'short_version' => $shortVersion
          ])
        ]
      ]);
    }

    $newShortUrl = new ShortUrl(
      originalUrl: $originalUrl,
      shortVersion: $shortVersion,
    );

    // Insert
    $inserted = $this->shortUrlRepository->insert($newShortUrl);
    if (!$inserted) {
      return new JsonResponse([
        'ok' => FALSE,
        'messages' => [
          [
            'type' => 'error',
            'message' => 'Failed when saving your entry. Try again.'
          ]
        ],
      ]);
    }

    // Done
    return new JsonResponse([
      'ok' => TRUE,
      'data' => [
        'url' => $newShortUrl
      ],
      'messages' => [
        [
          'type' => 'success',
          'message' => 'URL created'
        ]
      ]
    ]);
  }
}