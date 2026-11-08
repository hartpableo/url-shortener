<?php

namespace UrlShortener\Repository;

use UrlShortener\Entity\IpAddress;

class IpAddressRepository extends RepositoryBase {
  protected string $table = 'ip_address';

  public function findBy(array $args): ?IpAddress {
    $entity = parent::findBy($args);
    return $entity ? new IpAddress(
      id: $entity['id'],
      ipAddress: $entity['ip_address'],
      shortUrl: $entity['short_url']
    ) : NULL;
  }

  public function findAll(
    array $args = [],
    ?int $offset = NULL,
    ?int $limit = NULL
  ): array {
    $all = parent::findAll($args, $offset, $limit);
    $all['data'] = array_map(
      function ($entity) {
        return new IpAddress(
          id: $entity['id'],
          ipAddress: $entity['ip_address'],
          shortUrl: $entity['short_url']
        )->toArray();
      },
      $all['data']
    );

    return $all;
  }
}