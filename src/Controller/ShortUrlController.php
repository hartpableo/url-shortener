<?php

namespace UrlShortener\Controller;

use UrlShortener\Entity\IpAddress;
use UrlShortener\Entity\ShortUrl;
use UrlShortener\Repository\IpAddressRepository;
use UrlShortener\Repository\ShortUrlRepository;
use UrlShortener\Response\HtmlResponse;
use UrlShortener\Response\InternalServerErrorResponse;
use UrlShortener\Response\JsonResponse;
use UrlShortener\Response\NotFoundResponse;
use UrlShortener\Response\RedirectResponse;

class ShortUrlController {
  private ShortUrlRepository $shortUrlRepository;
  private IpAddressRepository $ipAddressRepository;

  public function __construct() {
    $this->shortUrlRepository = new ShortUrlRepository();
    $this->ipAddressRepository = new IpAddressRepository();
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

  public function route(): InternalServerErrorResponse|string|RedirectResponse|null {
    $request = request();
    $session = session();
    $shortUrl = $this->shortUrlRepository->findBy([
      'short_version' => urlencode($request->uri)
    ]);

    if (!$shortUrl) {
      return new NotFoundResponse(
        return: TRUE
      )();
    }

    // Save unique visitor
    $ipAddress = $this->ipAddressRepository->findBy([
      'short_url' => $shortUrl->id,
      'ip_address' => IpAddress::hashIpAddress($request->ip)
    ]);
    if (empty($ipAddress)) {
      $saved = $this->ipAddressRepository->insert(new IpAddress(
        ipAddress: IpAddress::hashIpAddress($request->ip),
        shortUrl: $shortUrl->id
      ));

      if (!$saved) {
        return new InternalServerErrorResponse();
      }
    }

    // Increment visitor count
    $cacheKey = 'visitor_' . md5($shortUrl->id . $request->ip);
    if (empty($session->get($cacheKey)) || (time() - $session->get($cacheKey)) > 2) {
      $session->set($cacheKey, time());
      $updated = $this->shortUrlRepository->update($shortUrl->withIncrementedVisit());
      if (!$updated) {
        return new InternalServerErrorResponse(
          return: TRUE
        )();
      }
    }

    return new RedirectResponse(urldecode($shortUrl->originalUrl));
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
    $shortVersion = urlencode('/' . trim($shortVersion, '/'));

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

  public function delete(): JsonResponse {
    $request = request();

    if ($request->method === 'OPTIONS') {
//      error_log('is options header' . PHP_EOL, 3, __DIR__ . '/test.log');
      return new JsonResponse([
        'ok' => TRUE,
      ], 204);
    }

    $deleted = $this->shortUrlRepository->delete([
      'id' => $request->input('id')
    ]);

    if (!$deleted) {
      return new JsonResponse(
        [
          'ok' => FALSE,
          'messages' => [
            [
              'type' => 'error',
              'message' => 'Failed when deleting your entry. Try again.'
            ]
          ]
        ],
        500
      );
    }

    return new JsonResponse(
      [
        'ok' => TRUE,
        'messages' => [
          [
            'type' => 'success',
            'message' => 'Removed short URL.'
          ]
        ]
      ],
    );
  }
}