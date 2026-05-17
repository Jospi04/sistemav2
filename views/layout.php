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

    <!-- HOJA DE ESTILOS PREMIUM PARA ALERTAS FLOTANTES GLOBAL (REEMPLAZO DE ALERT) -->
    <style>
        .premium-toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 999999;
            pointer-events: none;
        }

        .premium-toast-item {
            pointer-events: auto;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 14px;
            padding: 14px 20px;
            min-width: 300px;
            max-width: 400px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: rgba(0, 0, 0, 0.08) 0px 10px 30px -10px, rgba(0, 0, 0, 0.02) 0px 1px 3px;
            animation: slideInToast 0.4s cubic-bezier(0.1, 0.8, 0.2, 1) forwards;
            position: relative;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        @keyframes slideInToast {
            from {
                opacity: 0;
                transform: translateX(60px) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0) translateY(0);
            }
        }

        @keyframes fadeOutToast {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(60px);
            }
        }

        .toast-success {
            border-left: 4px solid var(--success-color);
            background: rgba(246, 253, 240, 0.85); /* Verde translúcido */
        }
        .toast-success .toast-icon-box {
            color: var(--success-color);
        }
        .toast-success .toast-message {
            color: #1b4311;
        }

        .toast-error {
            border-left: 4px solid #ef4444;
            background: rgba(254, 242, 242, 0.85); /* Crimson translúcido */
        }
        .toast-error .toast-icon-box {
            color: #ef4444;
        }
        .toast-error .toast-message {
            color: #7f1d1d;
        }

        .toast-warning {
            border-left: 4px solid #f59e0b;
            background: rgba(255, 251, 235, 0.85); /* Ámbar translúcido */
        }
        .toast-warning .toast-icon-box {
            color: #f59e0b;
        }
        .toast-warning .toast-message {
            color: #78350f;
        }

        .toast-icon-box {
            font-size: 1.4rem;
            display: flex;
            align-items: center;
        }

        .toast-content {
            flex: 1;
        }

        .toast-message {
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .toast-close {
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            transition: color 0.2s;
            margin-left: 4px;
        }

        .toast-close:hover {
            color: var(--text-main);
        }
    </style>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const layoutContainer = document.querySelector('.layout-container');
        
        // Cargar estado de colapsado desde localStorage
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            layoutContainer.classList.add('sidebar-collapsed');
        }
        
        toggleBtn.addEventListener('click', function() {
            layoutContainer.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', layoutContainer.classList.contains('sidebar-collapsed'));
        });
    });

    // FUNCIÓN DE ALERTA/NOTIFICACIÓN PREMIUM TOAST (REEMPLAZO UNIVERSAL DE ALERT)
    window.showPremiumToast = function(message, type = 'success') {
        const container = document.getElementById('premiumToastContainer');
        if (!container) return;

        // Crear la notificación
        const toast = document.createElement('div');
        toast.className = `premium-toast-item toast-${type}`;
        
        // Asignar icono según tipo
        let icon = "<i class='bx bx-check-circle'></i>";
        if (type === 'error') {
            icon = "<i class='bx bx-error-circle'></i>";
        } else if (type === 'warning') {
            icon = "<i class='bx bx-alarm-exclamation'></i>";
        }

        toast.innerHTML = `
            <div class="toast-icon-box">${icon}</div>
            <div class="toast-content" style="flex: 1;">
                <span class="toast-message" style="display: block;">${message}</span>
            </div>
            <span class="toast-close" onclick="this.parentElement.remove()" style="cursor: pointer;"><i class='bx bx-x'></i></span>
        `;

        container.appendChild(toast);

        // Auto remover después de 4 segundos con animación suave
        setTimeout(() => {
            if (toast && toast.parentElement) {
                toast.style.animation = 'fadeOutToast 0.4s ease forwards';
                setTimeout(() => {
                    if (toast && toast.parentElement) {
                        toast.remove();
                    }
                }, 400);
            }
        }, 4000);
    };
    </script>
</body>

</html>