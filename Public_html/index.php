<?php
session_start();
require_once __DIR__ . '/includes/conexion.php'; // crea $pdo (PDO)


// Procesar el formulario de login
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || $password === '') {
        $error = 'Ingresa un correo válido y tu contraseña.';
    } else {
        try {
            // Busca al usuario por email
            $sql = "SELECT id_usuario, nombre, email, contrasenia FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            // Verifica la contraseña (hash seguro con password_hash)
            if ($user && password_verify($password, $user['contrasenia'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']   = $user['id_usuario'];
                $_SESSION['user_name'] = $user['nombre'] ?? $user['email'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Credenciales inválidas.';
            }
        } catch (Throwable $e) {
            // Opcional: loggear $e->getMessage()
            $error = 'Ocurrió un error al iniciar sesión.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./assets/img/icon_controlBox.png">
    <title>ControlBox</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
  
</head>
<body>
    <!-- Sección de imagen a la izquierda -->
    <section class="image-section">
        <div class="image-content">
            <h1>ControlBox</h1>
            <p>Una plataforma diseñada para optimizar el control de tus productos de forma eficiente y moderna.</p>
            
            <div class="features">
                <div class="feature">
                    <i class="fas fa-chart-line"></i>
                    <div>
                        <h3>Análisis en Tiempo Real</h3>
                        <p>Monitorea tus productos con estadísticas actualizadas</p>
                    </div>
                </div>
                <div class="feature">
                    <i class="fas fa-lock"></i>
                    <div>
                        <h3>Seguridad Garantizada</h3>
                        <p>Protección avanzada para tus datos más sensibles</p>
                    </div>
                </div>
                <div class="feature">
                    <i class="fas fa-sync-alt"></i>
                    <div>
                        <h3>Sincronización Automática</h3>
                        <p>Tus datos siempre actualizados en todos tus dispositivos</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de login a la derecha -->
    <section class="login-section">
        <div class="login-container">
            <img src="./assets/img/icono_controlBox_completo.png" alt="ControlBox Logo" class="logo">            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" class="form-control" placeholder="tucorreo@ejemplo.com" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                
                <button type="submit" name="login" class="btn">Iniciar Sesión</button>
                
                <?php if (!empty($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </section>
</body>
</html>