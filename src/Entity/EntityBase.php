<?php

namespace UrlShortener\Entity;

use ReflectionClass;
use ReflectionProperty;

class EntityBase {
  private function sanitizePropertyToColumn(string $property): string {
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));
  }

  public function toArray(bool $includePrivateSet = FALSE): array {
    $propNames = [];
    $propValues = [];
    $ref = new ReflectionClass($this);
    $refProps = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
    if (!$includePrivateSet) {
      $refProps = array_filter($refProps, fn($property) => !$property->isPrivateSet());
    }

    foreach ($refProps as $prop) {
      $propNames[] = $prop->getName();
      $propValues[] = $prop->getValue($this);
    }

//    dd([$propNames, $propValues]);
//    $props = get_object_vars($this);
    $propsToColumns = array_map(
      [$this, 'sanitizePropertyToColumn'],
      $propNames
    );

//    dd($propsToColumns);
    return array_combine($propsToColumns, $propValues);
  }
}