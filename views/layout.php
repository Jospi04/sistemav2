<?php
// Validar sesión activa de forma segura
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login');
    exit;
}

$nombreUsuario = $_SESSION['nombre'] ?? 'Usuario';
$rolUsuario = $_SESSION['rol'] ?? 'operario';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOSPERÚ - Operaciones del Grifo</title>
    <!-- Importación de Fuente Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Boxicons CDN para Iconos Vectoriales Profesionales -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Enlace al CSS Modular del Layout -->
    <link rel="stylesheet" href="assets/css/layout.css">

    <!-- Enlace dinámico al CSS modular de la vista hija si corresponde -->
    <?php if (isset($extraCss) && !empty($extraCss)): ?>
        <link rel="stylesheet" href="assets/css/<?php echo $extraCss; ?>">
    <?php endif; ?>

    <!-- Identidad de Marca: Favicon para Pestañas del Navegador -->
    <link rel="icon" type="image/png" href="assets/images/icon.png">
</head>

<body>
    <!-- CONTENEDOR DE ALERTAS DE LUJO -->
    <div id="premiumToastContainer" class="premium-toast-container no-print"></div>

    <div class="layout-container">

        <!-- SIDEBAR DE NAVEGACIÓN LATERAL -->
        <aside class="sidebar">
            <!-- Logotipo Corporativo -->
            <div class="sidebar-brand">
                <div class="brand-link" style="display: flex; align-items: center; color: var(--color-sidebar-text); gap: 8px; cursor: default; user-select: none;">
                    <svg class="logo-leaf-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px; flex-shrink: 0; color: #fbfdf6;">
                        <path d="M12 2C6.48 2 2 6.48 2 12c0 3.65 1.95 6.84 4.88 8.61l.03-.03c.59-.59.95-1.4.95-2.3 0-1.79-1.46-3.25-3.25-3.25h-.06C4.2 13.9 4 12.97 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 .97-.2 1.9-.55 2.75l-.01.03c-.22.53-.55.99-.95 1.35l-.03.03c-.59.59-.95 1.4-.95 2.3 0 1.79 1.46 3.25 3.25 3.25.04 0 .07 0 .11-.01C19.78 18.06 21.6 15.22 21.6 12c0-5.52-4.48-10-10-10zm-1.25 15.5c0-.69.56-1.25 1.25-1.25s1.25.56 1.25 1.25-.56 1.25-1.25 1.25-1.25-.56-1.25-1.25z" />
                    </svg>
                    <span class="logo-text" style="font-family: 'Inter', sans-serif; font-size: 18px; font-weight: 700; letter-spacing: -0.04em;">JOSPERÚ</span>
                </div>
            </div>

            <!-- Menú de Opciones -->
            <nav class="sidebar-nav">
                <!-- Panel de Control (Dashboard) - Accesible a todos (Admin y Griferos) -->
                <a href="dashboard" class="nav-item <?php echo ($activePage ?? '') === 'dashboard' ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="bx bx-bar-chart-alt-2"></i></span>
                    <span class="nav-text">Panel de Control</span>
                </a>

                <!-- Despacho de Ventas - Accesible a todos -->
                <a href="ventas" class="nav-item <?php echo ($activePage ?? '') === 'ventas' ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="bx bx-gas-pump"></i></span>
                    <span class="nav-text">Despacho de Ventas</span>
                </a>

                <!-- Comprobante Boleta - Accesible a todos -->
                <a href="boleta" class="nav-item <?php echo ($activePage ?? '') === 'boleta' ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="bx bx-receipt"></i></span>
                    <span class="nav-text">Comprobante Boleta</span>
                </a>

                <!-- Accesos Exclusivos del Administrador -->
                <?php if ($rolUsuario === 'admin'): ?>
                    <a href="reportes" class="nav-item <?php echo ($activePage ?? '') === 'reportes' ? 'active' : ''; ?>">
                        <span class="nav-icon"><i class="bx bx-line-chart"></i></span>
                        <span class="nav-text">Reportes Financieros</span>
                    </a>
                    <a href="usuarios" class="nav-item <?php echo ($activePage ?? '') === 'usuarios' ? 'active' : ''; ?>">
                        <span class="nav-icon"><i class="bx bx-user-plus"></i></span>
                        <span class="nav-text">Crear Vendedores</span>
                    </a>
                <?php endif; ?>
            </nav>

            <!-- Sección Inferior (Perfil y Logout) -->
            <div class="sidebar-footer">
                <div class="user-card">
                    <div class="avatar">
                        <?php echo strtoupper(substr($nombreUsuario, 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <span class="username"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                        <span class="user-role"><?php echo $rolUsuario === 'admin' ? 'Administrador' : 'Operador'; ?></span>
                    </div>
                </div>

                <a href="logout" class="btn-logout">
                    <span class="logout-icon"><i class="bx bx-log-out"></i></span>
                    <span class="nav-text">Cerrar Sesión</span>
                </a>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="content-wrapper">

            <!-- CABECERA SUPERIOR (HEADER) -->
            <header class="header-top">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <button type="button" id="sidebarToggle" class="btn-toggle-sidebar" style="background: none; border: none; font-size: 1.5rem; color: var(--text-main); cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 6px; transition: background-color 0.2s; color: var(--text-main);">
                        <i class="bx bx-menu"></i>
                    </button>
                    <div class="header-title" style="display: flex; align-items: center;">
                        <h1 style="margin: 0;"><?php echo htmlspecialchars($pageTitle ?? 'Panel de Operaciones'); ?></h1>
                        <span class="header-badge">Terminal Activo #01</span>
                    </div>
                </div>

                <div class="header-meta">
                    <div class="status-pill">
                        <span class="status-dot"></span>
                        <span class="status-text">Surtidores Conectados</span>
                    </div>
                    <div class="header-date">
                        <span class="date-text"><?php echo date('d/m/Y'); ?></span>
                    </div>
                </div>
            </header>

            <!-- ÁREA DE CONTENIDO DINÁMICO -->
            <main class="main-body">
                <?php
                if (isset($viewFile) && file_exists(BASE_DIR . '/views/' . $viewFile)) {
                    require_once BASE_DIR . '/views/' . $viewFile;
                } else {
                    // Contenido por defecto (Welcome Card)
                    echo '<div class="welcome-box">
                        <h2>Bienvenido al Centro de Control de JOSPERÚ</h2>
                        <div class="divider-small"></div>
                        <p>Selecciona una opción del menú de navegación lateral para interactuar con los flujos de despacho de combustibles, emisión de boletas o reportes del grifo.</p>
                    </div>';
                }
                ?>
            </main>

        </div>

    </div>

    <!-- Enlace al JS Modular del Layout Maestro -->
    <script src="assets/js/layout.js"></script>

    <!-- Enlace dinámico a scripts específicos de la vista hija si corresponde -->
    <?php if (isset($extraJs) && !empty($extraJs)): ?>
        <script src="assets/js/<?php echo $extraJs; ?>"></script>
    <?php endif; ?>
</body>

</html>