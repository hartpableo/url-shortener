<?php

use UrlShortener\Database;

if (php_sapi_name() !== 'cli') {
  die();
}

require_once __DIR__ . '/autoloader.php';

$db = new Database()->getConnection();
$dbMigrated = $db->exec(<<<SQL
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
SQL);

if ($dbMigrated === FALSE) {
  echo "\033[31mError with migration.\033[0m\n";
  exit(1);
}

echo "\033[32mMigration complete!\033[0m\n";