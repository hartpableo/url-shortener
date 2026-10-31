<?php

use UrlShortener\CreateShortUrl;

return [
  [
    'uri' => '/',
    'callback' => [new CreateShortUrl(), 'index'],
    'method' => ['GET'],
  ],
  [
    'uri' => '/create',
    'callback' => [new CreateShortUrl(), 'create'],
    'method' => ['POST'],
  ]
];