<?php

namespace UrlShortener\Entity;

use DateTimeImmutable;

class ShortUrl extends EntityBase {
  public function __construct(
    private readonly ?int               $id = NULL,
    private readonly ?string            $originalUrl = NULL,
    private readonly ?string            $shortVersion = NULL,
    private readonly ?int               $visitCount = NULL,
    private readonly ?string $createdAt = NULL,
    private readonly ?string $updatedAt = NULL,
  ) {
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