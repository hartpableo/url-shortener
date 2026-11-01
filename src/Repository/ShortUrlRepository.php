<?php

namespace UrlShortener\Repository;

use UrlShortener\Entity\ShortUrl;

class ShortUrlRepository extends RepositoryBase {
  protected string $table = 'short_url';

  public function findBy(array $args): ShortUrl {
    $entity = parent::findBy($args);
    return new ShortUrl(
      id: $entity['id'],
      originalUrl: $entity['original_url'],
      shortVersion: $entity['short_version'],
      visitCount: $entity['visit_count'],
      createdAt: $entity['created_at'],
      updatedAt: $entity['updated_at']
    );
  }
}