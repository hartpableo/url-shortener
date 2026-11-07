<?php

function dd(mixed $data, bool $die = TRUE): void {
  echo '<pre>';
  print_r($data);
  echo '</pre>';
  if ($die) {
    die();
  }
}