<?php
require '../bootstrap/app.php';

$statement = "
    CREATE TABLE `ads` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `text` varchar(255) DEFAULT NULL,
      `price` int(11) DEFAULT NULL,
      `limit` int(10) unsigned DEFAULT NULL,
      `banner` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=INNODB;
";

try {
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (PDOException $e) {
    exit($e->getMessage());
}