<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Core\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

try {
    $db = Database::getInstance()->getConnection();
    echo "✅ Conexión exitosa a la base de datos";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}