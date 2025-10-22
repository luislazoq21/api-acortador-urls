<?php
require __DIR__ . '/../config/database.php';

try {
    $sql = file_get_contents(__DIR__ . '/seed.sql');

    if ($sql === false) {
        throw new Exception("No se pudo leer el archivo seed.sql");
    }

    $pdo->exec("USE url_shortener;");

    $hashedPassword = password_hash('1234567890', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute(['Luis', 'luis@gmail.com', $hashedPassword]);

    $pdo->exec($sql);

    echo "✅ Datos de prueba insertados correctamente.\n";
} catch (PDOException $e) {
    echo "❌ Error al insertar datos: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "⚠️ " . $e->getMessage() . "\n";
}
