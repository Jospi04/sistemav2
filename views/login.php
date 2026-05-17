<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adaline — Autenticación</title>
    <!-- Importación de Outfit e Inter para Akkurat, y JetBrains Mono para fragmentMono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=JetBrains+Mono:wght@400;500&family=Outfit:wght@400;500;700&display=swap"
        rel="stylesheet">
    <!-- CSS Modular exclusivo del Login -->
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>

    <!-- Cuadrícula Decorativa de Fondo -->
    <div class="login-decor-overlay"></div>

    <div class="login-wrapper">
        <main class="login-card-adaline">

            <!-- Encabezado de Acceso -->
            <header class="login-header">
                <a href="home" class="logo-title">
                    <span class="logo-light">adaline</span>.admin
                </a>
                <p class="subtitle font-mono">Control de Acceso Operativo</p>
            </header>

            <!-- Alerta de Error en Verde/Marrón Orgánico (Adaline Style) -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert-forest-box" role="alert">
                    <div class="alert-icon-svg">
                        <!-- Icono SVG de Advertencia en Verde Valle -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="alert-svg">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
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

                <!-- Campo de Entrada: Usuario -->
                <div class="form-field-group">
                    <label for="username">Nombre de Usuario</label>
                    <div class="input-container">
                        <span class="field-icon">
                            <!-- Icono SVG de Usuario -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="field-svg">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </span>
                        <input type="text" id="username" name="usuario" required placeholder="Ej. administrador"
                            autocomplete="username">
                    </div>
                </div>

                <!-- Campo de Entrada: Contraseña -->
                <div class="form-field-group">
                    <label for="password">Contraseña Operativa</label>
                    <div class="input-container">
                        <span class="field-icon">
                            <!-- Icono SVG de Candado -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="field-svg">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            autocomplete="current-password">
                    </div>
                </div>

                <!-- Botones y Acciones en formato Lozenge (20px radius) -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary-action-submit">Iniciar Sesión</button>
                    <a href="home" class="btn-secondary-action-cancel">Volver al Portal</a>
                </div>

            </form>

            <!-- Pie de la Tarjeta -->
            <footer class="login-footer">
                <p class="font-mono">&copy; 2026 JOSPERÚn. Securizado.</p>
            </footer>

        </main>
    </div>

</body>

</html>