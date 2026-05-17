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
    <title>PetroAdmin - Operaciones del Grifo</title>
    <!-- Importación de Fuente Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Enlace al CSS Modular del Layout -->
    <link rel="stylesheet" href="assets/layout.css">
    
    <!-- Enlace dinámico al CSS modular de la vista hija si corresponde -->
    <?php if (isset($extraCss) && !empty($extraCss)): ?>
        <link rel="stylesheet" href="assets/<?php echo $extraCss; ?>">
    <?php endif; ?>
</head>
<body>

    <div class="layout-container">
        
        <!-- SIDEBAR DE NAVEGACIÓN LATERAL -->
        <aside class="sidebar">
            <!-- Logotipo Corporativo -->
            <div class="sidebar-brand">
                <a href="home" class="brand-link">
                    <span class="brand-accent">PETRO</span>ADMIN
                </a>
            </div>

            <!-- Menú de Opciones -->
            <nav class="sidebar-nav">
                <?php if ($rolUsuario === 'admin'): ?>
                    <a href="dashboard" class="nav-item <?php echo ($activePage ?? '') === 'dashboard' ? 'active' : ''; ?>">
                        <span class="nav-icon">📊</span> Panel de Control
                    </a>
                <?php endif; ?>
                
                <a href="ventas" class="nav-item <?php echo ($activePage ?? '') === 'ventas' ? 'active' : ''; ?>">
                    <span class="nav-icon">⚡</span> Despacho de Ventas
                </a>
                
                <a href="boleta" class="nav-item <?php echo ($activePage ?? '') === 'boleta' ? 'active' : ''; ?>">
                    <span class="nav-icon">🧾</span> Comprobante Boleta
                </a>
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
                    <span class="logout-icon">🚪</span> Cerrar Sesión
                </a>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="content-wrapper">
            
            <!-- CABECERA SUPERIOR (HEADER) -->
            <header class="header-top">
                <div class="header-title">
                    <h1><?php echo htmlspecialchars($pageTitle ?? 'Panel de Operaciones'); ?></h1>
                    <span class="header-badge">Terminal Activo #01</span>
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
                        <h2>Bienvenido al Centro de Control de PetroAdmin</h2>
                        <div class="divider-small"></div>
                        <p>Selecciona una opción del menú de navegación lateral para interactuar con los flujos de despacho de combustibles, emisión de boletas o reportes del grifo.</p>
                    </div>';
                }
                ?>
            </main>

        </div>

    </div>

</body>
</html>
