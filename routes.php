<?php

use UrlShortener\Controller\ShortUrlController;

return [
  [
    'uri' => '/',
    'callback' => [new ShortUrlController(), 'index'],
    'method' => ['GET'],
  ],
  [
    'uri' => '/create',
    'callback' => [new ShortUrlController(), 'create'],
    'method' => ['POST'],
  ]
];