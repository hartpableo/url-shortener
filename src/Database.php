<?php

namespace UrlShortener;

use PDO;

final class Database {
  private ?\PDO $instance = NULL;
  private string $type;
  private string $host;
  private string $username;
  private string $password;
  private string $name;
  private string $port;
  private string $charset;
  private array  $options;

  public function __construct() {
    $this->type = getenv('DB_TYPE');
    $this->host = getenv('DB_HOST');
    $this->port = getenv('DB_PORT');
    $this->username = getenv('DB_USER');
    $this->password = getenv('DB_PASS');
    $this->name = getenv('DB_NAME');
    $this->options = [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    $this->charset = 'utf8mb4';
  }

  public function getConnection(): PDO {
    if (empty($this->instance)) {
      $this->instance = new PDO(
        "{$this->type}:host={$this->host};port={$this->port};dbname={$this->name};charset={$this->charset}",
        $this->username,
        $this->password,
        $this->options
      );
    }

    return $this->instance;
  }
}