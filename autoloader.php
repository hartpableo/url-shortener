<?php

spl_autoload_register(function ($class) {
  $prefixLength = strlen('UrlShortener\\');
  if (strncmp('UrlShortener\\', $class, $prefixLength) !== 0) {
    return;
  }

  $className = substr($class, $prefixLength);
  $classFile = __DIR__ . '/src/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
  if (file_exists($classFile)) {
    require $classFile;
  }
});