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
    <title>Brosteria 24/7 - Sistema de Gestión</title>
    <!-- Importación de Fuente Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Boxicons CDN para Iconos Vectoriales Profesionales -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Enlace al CSS Modular del Layout -->
    <link rel="stylesheet" href="assets/css/layout.css?v=<?php echo time(); ?>">

    <!-- Enlace dinámico al CSS modular de la vista hija si corresponde -->
    <?php if (isset($extraCss) && !empty($extraCss)): ?>
        <link rel="stylesheet" href="assets/css/<?php echo $extraCss; ?>?v=<?php echo time(); ?>">
    <?php endif; ?>

    <!-- Identidad de Marca: Favicon para Pestañas del Navegador -->
    <link rel="icon" type="image/png" href="assets/images/comida.jpg">
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
                    <i class='bx bx-restaurant' style="font-size: 24px; color: #ffc107;"></i>
                    <span class="logo-text" style="font-family: 'Outfit', sans-serif; font-size: 19px; font-weight: 800; letter-spacing: -0.02em; color: #fff;">Brosteria 24/7</span>
                </div>
            </div>

            <!-- Menú de Opciones -->
            <nav class="sidebar-nav">
                <!-- Panel de Control (Dashboard) - Accesible a todos (Admin y Griferos) -->
                <a href="dashboard" class="nav-item <?php echo ($activePage ?? '') === 'dashboard' ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="bx bx-grid-alt"></i></span>
                    <span class="nav-text">Panel de Ventas</span>
                </a>

                <!-- Despacho de Ventas - Accesible a todos -->
                <a href="ventas" class="nav-item <?php echo ($activePage ?? '') === 'ventas' ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="bx bx-food-menu"></i></span>
                    <span class="nav-text">Tomar Pedido</span>
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
                        <span class="nav-text">Reportes de Caja</span>
                    </a>
                    <a href="usuarios" class="nav-item <?php echo ($activePage ?? '') === 'usuarios' ? 'active' : ''; ?>">
                        <span class="nav-icon"><i class="bx bx-user-plus"></i></span>
                        <span class="nav-text">Gestionar Personal</span>
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
                        <span class="user-role"><?php echo $rolUsuario === 'admin' ? 'Administrador' : 'Cajero'; ?></span>
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
                        <h1 style="margin: 0; font-family: 'Outfit', sans-serif;"><?php echo htmlspecialchars($pageTitle ?? 'Panel de Operaciones'); ?></h1>
                        <span class="header-badge">Caja Activa #01</span>
                    </div>
                </div>

                <div class="header-meta">
                    <div class="status-pill">
                        <span class="status-dot"></span>
                        <span class="status-text">Cocina en Línea</span>
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
                        <h2>Bienvenido al Centro de Control de Brosteria 24/7</h2>
                        <div class="divider-small"></div>
                        <p>Selecciona una opción del menú de navegación lateral para interactuar con los flujos de pedidos, emisión de boletas o reportes de la brostería.</p>
                    </div>';
                }
                ?>
            </main>

        </div>

    </div>

    <!-- Enlace al JS Modular del Layout Maestro -->
    <script src="assets/js/layout.js?v=<?php echo time(); ?>"></script>

    <!-- Enlace dinámico a scripts específicos de la vista hija si corresponde -->
    <?php if (isset($extraJs) && !empty($extraJs)): ?>
        <script src="assets/js/<?php echo $extraJs; ?>?v=<?php echo time(); ?>"></script>
    <?php endif; ?>
</body>

</html>