<?php

namespace UrlShortener\Entity;

use DateTimeImmutable;
use DateTimeZone;

class ShortUrl extends EntityBase {
  private ?string $createdAt;
  private ?string $updatedAt;

  public function __construct(
    private readonly ?int    $id = NULL,
    private readonly ?string $originalUrl = NULL,
    private readonly ?string $shortVersion = NULL,
    private readonly ?int    $visitCount = 0,
    ?string $createdAt = NULL,
    ?string $updatedAt = NULL,
  ) {
    $this->createdAt = $createdAt ?? new DateTimeImmutable('now', new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    $this->updatedAt  = $updatedAt ?? new DateTimeImmutable('now', new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getOriginalUrl(): ?string {
    return $this->originalUrl;
  }

  public function getShortVersion(): ?string {
    return $this->shortVersion;
  }

  public function getVisitCount(): ?int {
    return $this->visitCount;
  }

  public function getCreatedAt(): ?string {
    return $this->createdAt;
  }

  public function getUpdatedAt(): ?string {
    return $this->updatedAt;
  }
}