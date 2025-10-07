<?php
include "../includes/conexion.php";

// Obtener usuario actual de la sesión (asumiendo que está en $_SESSION)
session_start();
$usuario_actual_id = $_SESSION['id_usuario'] ?? null;
$usuario_actual_nombre = $_SESSION['nombre'] ?? 'Administrador';

// Procesar operaciones CRUD
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_usuario'])) {
        // Crear nuevo usuario
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $email = trim($_POST['email']);
        $contrasenia = $_POST['contrasenia'];
        
        try {
            // Validaciones
            if (empty($nombre) || empty($apellido) || empty($email) || empty($contrasenia)) {
                throw new Exception("Todos los campos son obligatorios");
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El formato del email no es válido");
            }
            
            if (strlen($contrasenia) < 6) {
                throw new Exception("La contraseña debe tener al menos 6 caracteres");
            }
            
            // Verificar si el email ya existe
            $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND visible = 1");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                throw new Exception("El email ya está registrado");
            }
            
            $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, contrasenia, creado_por) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $apellido, $email, $contrasenia_hash, $usuario_actual_id]);
            
            $mensaje = "Usuario creado exitosamente";
            $tipo_mensaje = "success";
            
        } catch (PDOException $e) {
            error_log("Error BD al crear usuario: " . $e->getMessage());
            $mensaje = "Error al crear usuario. Por favor, intente nuevamente.";
            $tipo_mensaje = "danger";
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }

    if (isset($_POST['actualizar_usuario'])) {
    // Actualizar usuario existente
    $id_usuario = $_POST['id_usuario'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $contrasenia = trim($_POST['contrasenia']);
    
        try {
            // Validar que el email no esté duplicado (excluyendo el usuario actual)
            $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ? AND visible = 1");
            $stmt->execute([$email, $id_usuario]);
            if ($stmt->fetch()) {
                throw new Exception("El email ya está en uso por otro usuario");
            }
            
            if (!empty($contrasenia)) {
                // Actualizar con nueva contraseña
                $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, contrasenia = ? WHERE id_usuario = ?");
                $stmt->execute([$nombre, $apellido, $email, $contrasenia_hash, $id_usuario]);
            } else {
                // Actualizar sin cambiar contraseña
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, email = ? WHERE id_usuario = ?");
                $stmt->execute([$nombre, $apellido, $email, $id_usuario]);
            }
            
            $mensaje = "Usuario actualizado exitosamente";
            $tipo_mensaje = "success";
            
        } catch (PDOException $e) {
            $mensaje = "Error de base de datos al actualizar usuario: " . $e->getMessage();
            $tipo_mensaje = "danger";
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
}


// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];
    
    try {
        // Validar que no se esté eliminando a sí mismo
        if ($id_eliminar == $usuario_actual_id) {
            throw new Exception("No puedes eliminar tu propio usuario");
        }
        
        // Verificar que el usuario existe
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE id_usuario = ? AND visible = 1");
        $stmt->execute([$id_eliminar]);
        if (!$stmt->fetch()) {
            throw new Exception("El usuario no existe o ya fue eliminado");
        }
        
        $stmt = $pdo->prepare("UPDATE usuarios SET visible = 0 WHERE id_usuario = ?");
        $stmt->execute([$id_eliminar]);
        
        $mensaje = "Usuario eliminado exitosamente";
        $tipo_mensaje = "success";
        
    } catch (PDOException $e) {
        error_log("Error BD al eliminar usuario: " . $e->getMessage());
        $mensaje = "Error al eliminar usuario. Por favor, intente nuevamente.";
        $tipo_mensaje = "danger";
    } catch (Exception $e) {
        $mensaje = $e->getMessage();
        $tipo_mensaje = "danger";
    }
}

// Obtener usuarios para la lista
$stmt = $pdo->query("SELECT u.*, uc.nombre as creador_nombre 
                     FROM usuarios u 
                     LEFT JOIN usuarios uc ON u.creado_por = uc.id_usuario 
                     WHERE u.visible = 1 
                     ORDER BY u.fecha_creacion DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener usuario para edición
$usuario_editar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$_GET['editar']]);
    $usuario_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

include "../includes/header_menu.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - CRUD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 350px;
            z-index: 1000;
        }
        
        .alert {
            transform: translateX(400px);
            opacity: 0;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }
        
        .alert.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .acciones-btn {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-icon {
            padding: 0.5rem;
            width: 2.5rem;
            height: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Gestión de Usuarios</h1>
        
        <!-- Alertas -->
        <?php if ($mensaje): ?>
        <div class="alert-container">
            <div class="alert alert-<?php echo $tipo_mensaje; ?> show">
                <i class="fas <?php echo $tipo_mensaje === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <div>
                    <strong><?php echo $tipo_mensaje === 'success' ? 'Éxito!' : 'Error!'; ?></strong> 
                    <?php echo $mensaje; ?>
                </div>
                <button class="alert-close">&times;</button>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Pestañas -->
        <section class="section">
            <div class="section-title">
                <i class="fas fa-users"></i>
                <h2>Administración de Usuarios</h2>
            </div>
            <div class="tabs">
                <div class="tab active" data-tab="crear">Crear Usuario</div>
                <div class="tab" data-tab="lista">Lista de Usuarios</div>
            </div>
            
            <!-- Pestaña 1: Crear Usuario -->
            <div class="tab-content active" id="crear">
                <div>
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $usuario_editar ? 'Editar Usuario' : 'Nuevo Usuario'; ?></h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="form-row">
                            <?php if ($usuario_editar): ?>
                            <input type="hidden" name="id_usuario" value="<?php echo $usuario_editar['id_usuario']; ?>">
                            <?php endif; ?>
                            
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control" 
                                        value="<?php echo $usuario_editar ? $usuario_editar['nombre'] : ''; ?>" 
                                        placeholder="Ingresa el nombre" required>
                                </div>
                                <div class="form-group">
                                    <label for="apellido">Apellido</label>
                                    <input type="text" id="apellido" name="apellido" class="form-control" 
                                        value="<?php echo $usuario_editar ? $usuario_editar['apellido'] : ''; ?>" 
                                        placeholder="Ingresa el apellido" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" 
                                        value="<?php echo $usuario_editar ? $usuario_editar['email'] : ''; ?>" 
                                        placeholder="ejemplo@correo.com" required>
                                </div>
                                <!-- Password field for both create and edit -->
                                <div class="form-group">
                                    <label for="contrasenia"><?php echo $usuario_editar ? 'Nueva Contraseña (dejar en blanco para no cambiar)' : 'Contraseña'; ?></label>
                                    <input type="password" id="contrasenia" name="contrasenia" class="form-control" 
                                        placeholder="<?php echo $usuario_editar ? 'Opcional' : 'Ingresa la contraseña'; ?>" 
                                        <?php if (!$usuario_editar) echo 'required'; ?>>
                                </div>
                            </div>
                            <div class="form-group" style="width: 100%; margin-top: 1rem;">
                                <button type="submit" name="<?php echo $usuario_editar ? 'actualizar_usuario' : 'crear_usuario'; ?>" 
                                        class="btn <?php echo $usuario_editar ? 'btn-accent' : ''; ?>">
                                    <i class="fas <?php echo $usuario_editar ? 'fa-save' : 'fa-plus'; ?>"></i>
                                    <?php echo $usuario_editar ? 'Actualizar Usuario' : 'Crear Usuario'; ?>
                                </button>
                                <?php if ($usuario_editar): ?>
                                <a href="?cancelar=1" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Pestaña 2: Lista de Usuarios -->
            <div class="tab-content" id="lista">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Fecha Creación</th>
                                <th>Creado Por</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_creacion'])); ?></td>
                                <td>
                                    <span class="badge">
                                        <?php echo $usuario['creador_nombre'] ? htmlspecialchars($usuario['creador_nombre']) : 'Sistema'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="acciones-btn">
                                        <a href="?editar=<?php echo $usuario['id_usuario']; ?>" 
                                           class="btn btn-small btn-accent btn-icon" 
                                           title="Editar usuario">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?eliminar=<?php echo $usuario['id_usuario']; ?>" 
                                           class="btn btn-small btn-secondary btn-icon" 
                                           title="Eliminar usuario"
                                           onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No hay usuarios registrados</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Funcionalidad para las pestañas
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                tab.classList.add('active');
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Animación para alertas
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('show')) {
                    setTimeout(() => {
                        alert.classList.remove('show');
                        setTimeout(() => {
                            alert.remove();
                        }, 500);
                    }, 5000);
                }
            });

            // Cerrar alertas manualmente
            document.querySelectorAll('.alert-close').forEach(closeBtn => {
                closeBtn.addEventListener('click', function() {
                    const alert = this.closest('.alert');
                    alert.classList.remove('show');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                });
            });

            // Si estamos editando, cambiar a la pestaña de crear/editar
            <?php if ($usuario_editar): ?>
            document.querySelector('.tab[data-tab="crear"]').click();
            <?php endif; ?>
        });
    </script>
</body>
</html>