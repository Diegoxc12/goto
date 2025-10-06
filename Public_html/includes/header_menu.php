<?php
$pagina_actual = basename($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/header_menu.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar collapsed">

        
        <div class="logo-container">
            <img id="logo_imagen" alt="">
        </div>
        
        <ul class="nav-links">
            <li class="nav-item">
                <a href="/includes/dashboard.php" class="nav-link <?php echo ($pagina_actual=='dashboard.php')?'active':''; ?>">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="/includes/dashboard.php" class="nav-link <?php echo ($pagina_actual=='usuarios.php')?'active':''; ?>">
                <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </li>

            <li class="nav-item has-submenu <?php echo (in_array($pagina_actual, ['import_form.php', 'imagenes_producto_batch.php'])) ? 'active' : ''; ?>">
                <a href="#" class="nav-link">
                    <i class="fas fa-folder-open"></i> <!-- Carpeta abierta = sección Inventario -->
                    <span>Inventario</span>
                </a>
                <ul class="submenu-items <?php echo (in_array($pagina_actual, ['import_form.php', 'imagenes_producto_batch.php'])) ? 'submenu-active' : ''; ?>">

                    <li class="nav-item">
                        <a href="../includes/imagenes_producto_batch.php" class="nav-link submenu-link <?php echo ($pagina_actual == 'imagenes_producto_batch.php') ? 'active' : ''; ?>">
                            <i class="fas fa-image"></i> <!-- Imagen = fotos -->
                            <span>Ejemplo</span>
                        </a>
                    </li>
                </ul>
            </li>

            
            <li class="nav-item">
                <a href="../logout.php" class="nav-link <?php echo ($pagina_actual=='logout.php')?'active':''; ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Salir</span>
                </a>
            </li>
        </ul>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    const logo = document.getElementById("logo_imagen");
    const sidebar = document.querySelector('.sidebar');
    
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.style.marginLeft = sidebar.classList.contains('collapsed') ? '90px' : '260px';
    }

    if (!logo) {
        console.error("No se encontró el elemento con ID 'logo_imagen'");
        return;
    }

    const basePath = "../assets/img/";
    const lightLogo = "icono_controlBox_completo.png";
    const darkLogo = "icono_controlBox_completo_oscuro.png";

    function actualizarLogo() {
        const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const logoFile = isDarkMode ? darkLogo : lightLogo;
        logo.src = basePath + logoFile;
    }

    actualizarLogo();
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', actualizarLogo);

    // Abrir sidebar al pasar el mouse si está colapsado
    sidebar.addEventListener('mouseenter', () => {
        if (sidebar.classList.contains('collapsed')) {
            sidebar.classList.remove('collapsed');
            if (mainContent) mainContent.style.marginLeft = '260px';
        }
    });

    sidebar.addEventListener('mouseleave', () => {
        if (!sidebar.classList.contains('collapsed')) {
            sidebar.classList.add('collapsed');
            if (mainContent) mainContent.style.marginLeft = '90px';
        }
    });

    // Manejar submenús
    const hasSubmenuItems = document.querySelectorAll('.has-submenu');
    hasSubmenuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' && !sidebar.classList.contains('collapsed')) {
                e.preventDefault();
                const submenu = this.querySelector('.submenu-items');
                submenu.classList.toggle('submenu-active');
                this.classList.toggle('active');
            }
        });
    });
});
</script>

</body>
</html>