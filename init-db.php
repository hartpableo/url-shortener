<?php

use UrlShortener\Database;

if (php_sapi_name() !== 'cli') {
  die();
}

require_once __DIR__ . '/autoloader.php';

$db = new Database()->getConnection();
$dbMigrated = $db->exec(<<<SQL
-- Start fresh
DROP TABLE IF EXISTS `ip_address`;
DROP TABLE IF EXISTS `short_url`;

-- Set timezone to UTC
SET time_zone = '+00:00';

-- Create short_url table
CREATE TABLE IF NOT EXISTS `short_url` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `original_url` VARCHAR(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `short_version` VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `visit_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create ip_address table
CREATE TABLE IF NOT EXISTS `ip_address` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip_address` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `short_url` INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add FK for ip_address relaionship with short_url
ALTER TABLE `ip_address`
  ADD CONSTRAINT FK_IP_ADDRESS_SHORT_URL FOREIGN KEY (short_url)
  REFERENCES `short_url` (id)
  ON DELETE CASCADE
;
SQL);

if ($dbMigrated === FALSE) {
  echo "\033[31mError with migration.\033[0m\n";
  exit(1);
}

echo "\033[32mMigration complete!\033[0m\n";

$tables = ['short_url', 'ip_address'];

foreach ($tables as $table) {
//  echo "\033[1;34m--- Columns in table: `{$table}` ---\033[0m\n";

  // Use query() instead of exec() to fetch data
  $stmt = $db->query("SHOW COLUMNS FROM `{$table}`");

  // Fetch and print each column's metadata
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo sprintf(
      "Field: %-15s | Type: %-12s | Null: %-3s | Key: %-3s\n",
      $row['Field'],
      $row['Type'],
      $row['Null'],
      $row['Key']
    );
  }
  echo PHP_EOL;
}