<?php

namespace UrlShortener\Entity;

use DateTimeImmutable;
use DateTimeZone;

class ShortUrl extends EntityBase {
  public function __construct(
    public ?int    $id = NULL,
    public ?string $originalUrl = NULL,
    public ?string $shortVersion = NULL,
    public ?int    $visitCount = 0,
    public ?string $createdAt = NULL,
    public ?string $updatedAt = NULL,
  ) {
    $now = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');

    $this->createdAt = $this->createdAt ?? $now;
    $this->updatedAt  = $this->updatedAt ?? $now;
  }

  public function withIncrementedVisit(): self {
    $self = clone $this;
    $self->visitCount = $self->visitCount + 1;
    $self->updatedAt = new DateTimeImmutable('now', new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    return $self;
  }
}