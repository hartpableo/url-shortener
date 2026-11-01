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
  ]
];