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
    <style>
        :root {
            --primary-blue: #1e40af;
            --secondary-blue: #1d4381;
            --accent-blue: #60a5fa;
            --dark-blue: #132863;
            --darker-blue: #201f32;
            --text-light: #f8fafc;
            --text-gray: #cbd5e1;
            --background-dark: #090e1a;
            --card-dark: #1e293b;
            --input-dark: #334155;
            --hover-blue: #2563eb;
            --shadow-blue: rgba(59, 130, 246, 0.3);
            --border-radius: 0.5rem;
            --transition: all 0.1s ease;
            --darker-blueshadow: #96b2ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background: var(--background-dark);
            color: var(--text-light);
            line-height: 1.6;
        }

        /* Menú lateral */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 16rem;
            background: var(--darker-blue);
            z-index: 1000;
            padding: 1.25rem 0;
            transition: var(--transition);
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .logo-container {
            padding: 0 1.5rem 1rem;
            border-bottom: 1px solid var(--dark-blue);
            margin-bottom: 1.5rem;
        }

        #logo_imagen {
            width: 100%;
            height: auto;
        }

        .nav-links {
            padding: 0 1rem;
        }

        .nav-item {
            list-style: none;
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            color: var(--text-gray);
            border-radius: var(--border-radius);
            transition: var(--transition);
            gap: 0.75rem;
        }

        .nav-link:hover, 
        .nav-link.active {
            background: var(--dark-blue);
            color: var(--darker-blueshadow);
            transform: translateX(0.25rem);
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 1.5rem;
            text-align: center;
        }

        /* Botón de menú vertical */
        .menu-vertical-btn {
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-blue);
            color: var(--text-light);
            padding: 1rem 0.5rem;
            writing-mode: vertical-lr;
            text-orientation: mixed;
            cursor: pointer;
            z-index: 999;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            transition: var(--transition);
            font-weight: 500;
            letter-spacing: 1px;
        }

        .menu-vertical-btn:hover {
            background: var(--hover-blue);
            padding-left: 0.7rem;
        }

        .page-header {
            margin-bottom: 2.5rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--secondary-blue), var(--accent-blue));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .page-description {
            color: var(--text-gray);
            font-size: 1.1rem;
            max-width: 50rem;
            line-height: 1.8;
        }

        .page-description i {
            color: var(--accent-blue);
            margin-right: 0.5rem;
        }

        /* Submenús */
        .has-submenu > .nav-link::after {
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: auto;
        }

        .has-submenu.active .nav-link::after {
            transform: rotate(180deg);
        }

        .submenu-items {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            position: relative;
        }

        .submenu-active {
            max-height: 9.375rem;
        }

        /* Línea vertical para submenús desplegados */
        .submenu-active::before {
            content: '';
            position: absolute;
            left: 1.5rem;
            top: 0.5rem;
            bottom: 0.5rem;
            width: 2px;
            background: var(--accent-blue);
            border-radius: 1px;
        }

        .submenu-link {
            font-size: 0.9rem;
            padding-left: 2rem !important;
            position: relative;
        }

        /* ──────────────── BOTONES ──────────────── */
        .btn {
            width: 10%;
            padding: 1rem;
            border-radius: 15px;
            text-decoration: none;
            color: var(--text-light);
            background-color: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .btn:hover {
            background-color: var(--darker-blue);
            border: 1px solid var(--secondary-blue);
            color: var(--text-light);
        }

        .btn.active {
            background-color: var(--secondary-blue);
            color: white;
            border-color: var(--secondary-blue);
        }

        /* Contenedores y títulos */
        .wrap {
            margin: 0 auto;
        }

        .wrap > h1 {
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul class="nav-links">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo ($pagina_actual=='dashboard.php')?'active':''; ?>">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="../css_ejemplos.php" class="nav-link <?php echo ($pagina_actual=='css_ejemplos.php')?'active':''; ?>">
                    <i class="fa-solid fa-code nav-icon"></i>
                    <span>Css Ejemplos</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="../extras/usuarios.php" class="nav-link <?php echo ($pagina_actual=='usuarios.php')?'active':''; ?>">
                <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </li>

            <li class="nav-item has-submenu <?php echo (in_array($pagina_actual, ['import_form.php', 'imagenes_producto_batch.php'])) ? 'active' : ''; ?>">
                <a href="#" class="nav-link">
                    <i class="fas fa-folder-open"></i>
                    <span>Inventario</span>
                </a>
                <ul class="submenu-items <?php echo (in_array($pagina_actual, ['import_form.php', 'bodegas.php'])) ? 'submenu-active' : ''; ?>">
                    <li class="nav-item">
                        <a href="..\bodegas.php" class="nav-link submenu-link <?php echo ($pagina_actual == 'bodegas.php') ? 'active' : ''; ?>">
                            <span>Bodegas</span>
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

    <div class="menu-vertical-btn">MENÚ</div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.querySelector('.sidebar');
            const menuBtn = document.querySelector('.menu-vertical-btn');
            
            // Mostrar sidebar al pasar el mouse sobre el botón
            menuBtn.addEventListener('mouseenter', () => {
                sidebar.classList.add('active');
            });

            // Ocultar sidebar al quitar el mouse del sidebar
            sidebar.addEventListener('mouseleave', () => {
                sidebar.classList.remove('active');
            });

            // Manejar submenús
            const hasSubmenuItems = document.querySelectorAll('.has-submenu');
            hasSubmenuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if (e.target.tagName === 'A') {
                        e.preventDefault();
                        const submenu = this.querySelector('.submenu-items');
                        
                        if (submenu.classList.contains('submenu-active')) {
                            submenu.classList.remove('submenu-active');
                            submenu.style.maxHeight = '0';
                        } else {
                            submenu.classList.add('submenu-active');
                            submenu.style.maxHeight = submenu.scrollHeight + 'px';
                        }
                        this.classList.toggle('active');
                    }
                });
            });
        });
    </script>

</body>
</html>