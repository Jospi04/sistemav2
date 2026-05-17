<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetroAdmin - Control de Acceso</title>
    <!-- Importación de Fuente Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Enlace al CSS Modular exclusivo del Login -->
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>

    <div class="login-wrapper">
        <!-- Tarjeta Principal del Formulario -->
        <main class="login-card">
            
            <!-- Encabezado de la Tarjeta -->
            <header class="login-header">
                <a href="home" class="logo-back">
                    <span class="logo-accent">PETRO</span>ADMIN
                </a>
                <p class="subtitle">Portal del Trabajador y Administración</p>
            </header>

            <!-- Alerta de Error Elegante si existe en sesión -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert-error" role="alert">
                    <div class="alert-icon">⚠️</div>
                    <div class="alert-message">
                        <?php 
                            echo htmlspecialchars($_SESSION['error']); 
                            unset($_SESSION['error']); 
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulario de Acceso -->
            <form action="login" method="POST" class="login-form">
                
                <!-- Grupo de Entrada: Usuario -->
                <div class="input-group">
                    <label for="username">Nombre de Usuario</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="username" name="usuario" required placeholder="Ingrese su usuario (ej. admin)" autocomplete="username">
                    </div>
                </div>

                <!-- Grupo de Entrada: Contraseña -->
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" required placeholder="••••••••" autocomplete="current-password">
                    </div>
                </div>

                <!-- Botones y Acciones del Formulario -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Iniciar Sesión</button>
                    <a href="home" class="btn-cancel">Regresar al Inicio</a>
                </div>

            </form>

            <!-- Pie de la Tarjeta -->
            <footer class="login-footer">
                <p>&copy; 2026 PetroAdmin. Servidor seguro de despacho.</p>
            </footer>

        </main>
    </div>

</body>
</html>
