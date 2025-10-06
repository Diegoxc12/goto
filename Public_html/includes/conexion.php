<?php
// conexion.php  (debe crear $pdo)

$host = 'db'; // nombre del servicio de la base de datos en docker-compose
$db   = 'goto_db';
$user = 'user';
$pass = 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Forzar collation/charset de la sesión de MySQL
    $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("SET collation_connection = 'utf8mb4_unicode_ci'");
} catch (PDOException $e) {
    // Mostrar mensaje de error en pantalla y detener ejecución
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
