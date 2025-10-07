
<?php

include "./includes/header_menu.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Componentes Web - Estilo Azul Oscuro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <div class="main-content">
        <h1>Componentes Web - Estilo Azul Oscuro</h1>
        
        <!-- Botones -->
        <section class="section">
            <div class="section-title">
                <i class="fas fa-mouse-pointer"></i>
                <h2>Botones</h2>
            </div>
            <div class="btn-group">
                <button class="btn">Botón Primario</button>
                <button class="btn btn-secondary">Botón Secundario</button>
                <button class="btn btn-accent">Botón Acento</button>
                <button class="btn btn-outline">Botón Outline</button>
            </div>
            <div class="btn-group">
                <button class="btn btn-small">Botón Pequeño</button>
                <button class="btn">Botón Normal</button>
                <button class="btn btn-large">Botón Grande</button>
                <button class="btn btn-disabled">Deshabilitado</button>
            </div>
        </section>
        
        <!-- Formularios -->
        <section class="section">
            <div class="section-title">
                <i class="fas fa-edit"></i>
                <h2>Formularios</h2>
            </div>
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" class="form-control" placeholder="Ingresa tu nombre">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="ejemplo@correo.com">
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" class="form-control" placeholder="Ingresa tu contraseña">
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" class="form-control" placeholder="Escribe tu mensaje aquí..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="pais">País</label>
                        <select id="pais" class="form-control">
                            <option value="">Selecciona un país</option>
                            <option value="mx">México</option>
                            <option value="ar">Argentina</option>
                            <option value="co">Colombia</option>
                            <option value="es">España</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Checkboxes y Radios -->
        <section class="section">
            <div class="section-title">
                <i class="fas fa-check-square"></i>
                <h2>Checkboxes y Radio Buttons</h2>
            </div>
            <div class="form-row">
                <div class="form-col">
                    <h3>Checkboxes</h3>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="opcion1" checked>
                            <label for="opcion1">Opción 1</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="opcion2">
                            <label for="opcion2">Opción 2</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="opcion3">
                            <label for="opcion3">Opción 3</label>
                        </div>
                    </div>
                </div>
                <div class="form-col">
                    <h3>Radio Buttons</h3>
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" id="radio1" name="radio-group" checked>
                            <label for="radio1">Opción A</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="radio2" name="radio-group">
                            <label for="radio2">Opción B</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="radio3" name="radio-group">
                            <label for="radio3">Opción C</label>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Tablas -->
        <section class="section">
            <div class="section-title">
                <i class="fas fa-table"></i>
                <h2>Tablas</h2>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>País</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Juan Pérez</td>
                            <td>juan@example.com</td>
                            <td>México</td>
                            <td><span class="badge">Activo</span></td>
                        </tr>
                        <tr>
                            <td>María García</td>
                            <td>maria@example.com</td>
                            <td>Argentina</td>
                            <td><span class="badge">Inactivo</span></td>
                        </tr>
                        <tr>
                            <td>Carlos López</td>
                            <td>carlos@example.com</td>
                            <td>Colombia</td>
                            <td><span class="badge">Activo</span></td>
                        </tr>
                        <tr>
                            <td>Ana Martínez</td>
                            <td>ana@example.com</td>
                            <td>España</td>
                            <td><span class="badge">Pendiente</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        
        <!-- Tarjetas -->
        <section class="section">
            <div class="section-title">
                <i class="fas fa-id-card"></i>
                <h2>Tarjetas</h2>
            </div>
            <div class="grid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tarjeta de Información</h3>
                    </div>
                    <div class="card-body">
                        <p>Esta es una tarjeta de ejemplo con información relevante. Puede contener texto, imágenes o cualquier otro contenido.</p>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-small">Acción 1</button>
                        <button class="btn btn-small btn-secondary">Acción 2</button>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tarjeta con Icono</h3>
                    </div>
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="fas fa-star"></i>
                        </div>
                        <p>Esta tarjeta incluye un icono decorativo. Los iconos pueden mejorar la experiencia visual del usuario.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tarjeta de Estadísticas</h3>
                    </div>
                    <div class="card-body">
                        <h4>Progreso del Proyecto</h4>
                        <div class="progress mt-2">
                            <div class="progress-bar" style="width: 75%"></div>
                        </div>
                        <p class="mt-2">75% completado</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Alertas -->
        <div class="alert-container">
            <!-- Info Alert -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Information:</strong> This is an informative message.
                </div>
                <button class="alert-close">&times;</button>
            </div>
            
            <!-- Success Alert -->
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>Success!</strong> Your action was completed successfully.
                </div>
                <button class="alert-close">&times;</button>
            </div>
        </div>
        
        <!-- Pestañas -->
        <section class="section">
            <div class="section-title">
                <i class="fas fa-folder"></i>
                <h2>Pestañas</h2>
            </div>
            <div class="tabs">
                <div class="tab active" data-tab="tab1">Pestaña 1</div>
                <div class="tab" data-tab="tab2">Pestaña 2</div>
                <div class="tab" data-tab="tab3">Pestaña 3</div>
            </div>
            <div class="tab-content active" id="tab1">
                <h3>Contenido de la Pestaña 1</h3>
                <p>Este es el contenido de la primera pestaña. Puedes colocar cualquier tipo de información aquí.</p>
            </div>
            <div class="tab-content" id="tab2">
                <h3>Contenido de la Pestaña 2</h3>
                <p>Este es el contenido de la segunda pestaña. Incluye diferentes elementos y funcionalidades.</p>
            </div>
            <div class="tab-content" id="tab3">
                <h3>Contenido de la Pestaña 3</h3>
                <p>Este es el contenido de la tercera pestaña. Cada pestaña puede tener un diseño único.</p>
            </div>
        </section>
        
        <!-- Iconos y Flechas -->
        <section class="section">
            <div class="section-title">
                <i class="fas fa-arrows-alt"></i>
                <h2>Iconos y Flechas</h2>
            </div>
            <h3>Iconos Interactivos</h3>
            <div class="btn-group mt-2">
                <div class="icon-box">
                    <i class="fas fa-home"></i>
                </div>
                <div class="icon-box">
                    <i class="fas fa-search"></i>
                </div>
                <div class="icon-box">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="icon-box">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="icon-box">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            
            <h3 class="mt-3">Flechas de Dirección</h3>
            <div class="btn-group mt-2">
                <div class="icon-box">
                    <i class="arrow arrow-up"></i>
                </div>
                <div class="icon-box">
                    <i class="arrow arrow-down"></i>
                </div>
                <div class="icon-box">
                    <i class="arrow arrow-left"></i>
                </div>
                <div class="icon-box">
                    <i class="arrow arrow-right"></i>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Funcionalidad para las pestañas
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Remover clase active de todas las pestañas y contenidos
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Agregar clase active a la pestaña clickeada
                tab.classList.add('active');
                
                // Mostrar el contenido correspondiente
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        // Funcionalidad para los botones (solo para demostración)
        document.querySelectorAll('.btn:not(.btn-disabled)').forEach(button => {
            button.addEventListener('click', function() {
                if(this.classList.contains('active')) {
                    this.classList.remove('active');
                } else {
                    this.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>