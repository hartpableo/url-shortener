<?php

namespace UrlShortener;

use DateTimeImmutable;

class ShortUrl {
  public function __construct(
    public string $originalUrl,
    public string $shortUrl,
    public int $visitCount,
    public DateTimeImmutable $createdAt,
    public DateTimeImmutable $updatedAt,
  ) {}
}