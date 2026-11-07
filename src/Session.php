<?php

namespace UrlShortener;

final class Session {
  public function get(string $key) {
    return $_SESSION[$key] ?? NULL;
  }

  public function set(string $key, $value): void {
    $_SESSION[$key] = $value;
  }

  public function remove(string $key): void {
    unset($_SESSION[$key]);
  }
}