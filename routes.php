<?php

use UrlShortener\Controller\ShortUrlController;

return [
  [
    'uri' => '/',
    'callback' => [new ShortUrlController(), 'index'],
    'method' => ['GET', 'HEAD'],
  ],
  [
    'uri' => '/urls',
    'callback' => [new ShortUrlController(), 'getAll'],
    'method' => ['GET', 'HEAD'],
  ],
  [
    'uri' => '/create',
    'callback' => [new ShortUrlController(), 'create'],
    'method' => ['POST'],
  ],
  [
    'uri' => '/delete',
    'callback' => [new ShortUrlController(), 'delete'],
    'method' => ['DELETE', 'OPTIONS'],
  ],
  [
    'uri' => '/{:short_version}',
    'callback' => [new ShortUrlController(), 'route'],
    'args' => [
      'short_version' => function () {
        $uri = request()->uri;
        return !in_array($uri, ['/create', '/urls'])
          && $uri !== '/favicon.ico'
          && !preg_match('/\.(gif|jpe?g|png|ico|css|js)$/i', $uri);
      }
    ],
    'method' => ['GET'],
  ]
];