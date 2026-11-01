<?php

use UrlShortener\Controller\CreateShortUrl;

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