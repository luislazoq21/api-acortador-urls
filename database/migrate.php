<?php
require __DIR__ . '/../config/database.php';

try {
    // Leer todo el contenido del archivo schema.sql
    $sql = file_get_contents(__DIR__ . '/schema.sql');

    if ($sql === false) {
        throw new Exception("No se pudo leer el archivo schema.sql");
    }

    // Conexión usando PDO (usa la variable $pdo definida en database.php)
    $pdo->exec($sql);

    echo "✅ Migración ejecutada exitosamente.\n";
} catch (PDOException $e) {
    echo "❌ Error de base de datos: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "⚠️ Error: " . $e->getMessage() . "\n";
}
