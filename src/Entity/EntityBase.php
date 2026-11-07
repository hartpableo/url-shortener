<?php

namespace UrlShortener\Entity;

use ReflectionClass;
use ReflectionProperty;

class EntityBase {
  private function sanitizePropertyToColumn(string $property): string {
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));
  }

  public function toArray(): array {
    $props = get_object_vars($this);
    $propsToColumns = array_map(
      [$this, 'sanitizePropertyToColumn'],
      array_keys($props)
    );
    return array_combine($propsToColumns, array_values($props));
  }
}