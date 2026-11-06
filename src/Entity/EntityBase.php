<?php

namespace UrlShortener\Entity;

use ReflectionClass;
use ReflectionProperty;

class EntityBase {
  private function sanitizePropertyToColumn(string $property): string {
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));
  }

  public function toArray(): array {
//    $reflection = new ReflectionClass(static::class);
//    $properties = $reflection->getProperties();
//    $final_array = [];
//    foreach ($properties as $property) {
//      $method = "get{$property->getName()}";
//      if (method_exists(static::class, $method)) {
//        $final_array[$property->getName()] = $this->$method();
//      }
//    }

//    return $final_array;
    return get_object_vars($this);
  }

//  public function getPropColumns(): array {
//    $final_array = [];
//    $propMethods = $this->getPropMethods();
//
//    foreach ($propMethods as $pm) {
//      $columnName = $this->sanitizePropertyToColumn(
//        str_replace('get', '', $pm)
//      );
//      $final_array[$pm] = $columnName;
//    }
//
//    return $final_array;
//  }
//
//  public function getPropMethods(): array {
//    $allMethods = get_class_methods(static::class);
//    $inheritedMethods = get_class_methods(self::class);
//    return array_filter(
//      $allMethods,
//      fn($method) => str_starts_with($method, 'get') && !in_array($method, $inheritedMethods)
//    );
//  }
}