<?php

namespace UrlShortener\Entity;

use UrlShortener\Repository\ShortUrlRepository;

class IpAddress extends EntityBase {
  private ?ShortUrlRepository $shortUrlRepository;

  public function __construct(
    public ?int    $id = NULL,
    public ?string $ipAddress = NULL,
    public ?int    $shortUrl = NULL,
  ) {
    $this->shortUrlRepository = new ShortUrlRepository();
  }

  public function getShortUrl(): ?ShortUrl {
    return $this->shortUrlRepository->findBy(['id' => $this->shortUrl]);
  }
}