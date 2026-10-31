<?php

use UrlShortener\CreateShortUrl;

return [
  [
    'uri' => '/create',
    'callback' => [new CreateShortUrl(), 'create'],
    'method' => ['POST', 'GET'], // @todo: remove GET
  ]
];