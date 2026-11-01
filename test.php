<?php

use UrlShortener\Entity\ShortUrl;
use UrlShortener\Repository\ShortUrlRepository;

require __DIR__  . '/autoloader.php';

$shortUrl = new ShortUrl(
  originalUrl: urlencode('https://google.com'),
  shortVersion: htmlspecialchars('samplesite'),
  visitCount: 3,
  createdAt: new DateTimeImmutable('yesterday', new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
  updatedAt: new DateTimeImmutable('now', new DateTimeZone('UTC'))->format('Y-m-d H:i:s')
);
$shortUrlRepo = new ShortUrlRepository();
//print_r($shortUrl->getPropColumns());
//print_r(get_class_methods($shortUrl));
//$insertQuery = $shortUrlRepo->insert($shortUrl);
//print_r($insertQuery);
$reflection = new ReflectionClass($shortUrl);
print_r($reflection->getProperties());