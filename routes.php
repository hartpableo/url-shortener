<?php

use UrlShortener\Controller\ShortUrlController;

return [
  [
    'uri' => '/',
    'callback' => [new ShortUrlController(), 'index'],
    'method' => ['GET'],
  ],
  [
    'uri' => '/urls',
    'callback' => [new ShortUrlController(), 'getAll'],
    'method' => ['GET'],
  ],
  [
    'uri' => '/create',
    'callback' => [new ShortUrlController(), 'create'],
    'method' => ['POST'],
  ],
  [
    'uri' => '/{:short_version}',
    'callback' => [new ShortUrlController(), 'route'],
    'args' => [
      'short_version' => function () {
        $uri = request()->uri;
        return !in_array($uri, ['/create', '/urls']);
      }
    ],
    'method' => ['GET'],
  ]
];