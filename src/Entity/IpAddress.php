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
    $this->ipAddress = self::hashIpAddress($this->ipAddress);
    $this->shortUrlRepository = new ShortUrlRepository();
  }

  public function getShortUrl(): ?ShortUrl {
    return $this->shortUrlRepository->findBy(['id' => $this->shortUrl]);
  }

  public static function hashIpAddress(string $ipAddress): string {
    return hash_hmac('sha256', $ipAddress, '4017b8499fb51fbe050af00271605830');
  }
}