<?php
// db.php
$host = 'localhost';
$port = '5432';
$db   = 'inventario';
$user = 'postgres';        // <-- cambia si usas otro usuario
$pass = 'Espumas01';

$dsn = "pgsql:host=$host;port=$port;dbname=$db";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    // Mensaje simple para desarrollo
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit;
}
