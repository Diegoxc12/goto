<?php
// verificar_sesion.php
session_start();

// Incluir la conexión
require_once './conexion.php';

// Verificar si existe sesión
if (!isset($_SESSION['id_usuario'])) {
    // No hay sesión, redirigir al login
    header('Location: ../index.php');
    exit;
}

// Opcional: Verificar que el usuario exista en la base de datos
$id_usuario = $_SESSION['id_usuario'];

try {
    $stmt = $pdo->prepare("SELECT id_usuario, nombre, apellido, email FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        // Usuario no existe, destruir sesión y redirigir
        session_unset();
        session_destroy();
        header('Location: ../index.php');
        exit;
    }

    // Usuario existe, puedes usar $usuario['nombre'], $usuario['email'], etc.
} catch (PDOException $e) {
    // Error en la consulta, destruir sesión y redirigir
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}
