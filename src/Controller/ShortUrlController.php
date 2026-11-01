<?php

namespace UrlShortener\Controller;

use UrlShortener\Entity\ShortUrl;
use UrlShortener\Repository\ShortUrlRepository;
use UrlShortener\Response\HtmlResponse;
use UrlShortener\Response\JsonResponse;

class ShortUrlController {
  public function index(): HtmlResponse {
    return new HtmlResponse('home');
  }

  public function create(): JsonResponse {
    $shortUrlRepo = new ShortUrlRepository();
    $originalUrl = request()->input('originalUrl');
    $shortVersion = request()->input('shortVersion');
    $errors = [];
//    error_log(1);

    // Check required fields
    if (empty($originalUrl)) {
      $errors[] = 'The originalUrl field is required.';
    }

    if (empty($shortVersion)) {
      $errors[] = 'The shortVersion field is required.';
    }

    if (!empty($errors)) {
//      error_log(print_r([2, $errors], true));
      return new JsonResponse([
        'ok' => FALSE,
        'messages' => array_map(
          fn($err) => ['type' => 'error', 'message' => $err],
          $errors
        ),
      ]);
    }

//    error_log(3);
    // Sanitize
    $originalUrl = urlencode($originalUrl);
    $shortVersion = urlencode($shortVersion);

    // Bail if already exists
    $existing = $shortUrlRepo->checkIfExists([
      'original_url' => $originalUrl,
      'short_version' => $shortVersion
    ]);
    if ($existing) {
      return new JsonResponse([
        'ok' => TRUE,
        'messages' => [
          [
            'type' => 'info',
            'text' => 'Already exists'
          ]
        ],
        'data' => [
          'url' => $shortUrlRepo->findBy([
            'original_url' => $originalUrl,
            'short_version' => $shortVersion
          ])
        ]
      ]);
    }

//    error_log(4);
    $newShortUrl = new ShortUrl(
      originalUrl: $originalUrl,
      shortVersion: $shortVersion,
    );

    // Insert
    $inserted = $shortUrlRepo->insert($newShortUrl);
    if (!$inserted) {
//      error_log(5);
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

//    error_log(6);
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