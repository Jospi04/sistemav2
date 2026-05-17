<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetroAdmin - Sistema de Control y Despacho de Combustibles</title>
    <!-- Importación de Fuente Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Enlace obligatorio y exclusivo al CSS Modular -->
    <link rel="stylesheet" href="assets/home.css">
</head>
<body>

    <!-- Header / Barra de Navegación -->
    <header class="navbar">
        <div class="navbar-container">
            <a href="home" class="logo">
                <span class="logo-accent">PETRO</span>ADMIN
            </a>
            <nav class="nav-links">
                <a href="#caracteristicas">Características</a>
                <a href="#seguridad">Seguridad</a>
                <a href="login" class="btn-login">Acceso Personal</a>
            </nav>
        </div>
    </header>

    <!-- Sección Hero Principal -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Administración Integral para Estaciones de Servicio</h1>
                <p>Monitoreo digital de tanques de combustible, automatización de lecturas mecánicas de surtidores, auditorías financieras de cuadre de turnos y facturación electrónica instantánea en una plataforma corporativa de alta velocidad.</p>
                <div class="hero-actions">
                    <a href="login" class="btn-primary">Ingresar al Sistema</a>
                    <a href="#caracteristicas" class="btn-secondary">Conocer Más</a>
                </div>
            </div>
            <div class="hero-preview">
                <div class="preview-card">
                    <div class="preview-header">
                        <span class="indicator green"></span>
                        <span class="preview-title">Surtidores en Línea</span>
                    </div>
                    <div class="preview-body">
                        <div class="stat-row">
                            <span class="stat-label">Gasolina 95 Oct</span>
                            <span class="stat-value">Activo - Surtidor 1</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Diesel B5</span>
                            <span class="stat-value">Activo - Surtidor 4</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-label">
                                <span>Tanque Central Gasolina 95</span>
                                <span>70%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill fill-95"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Características -->
    <section id="caracteristicas" class="features">
        <div class="section-header">
            <h2>Gestión Avanzada y Control de Inventarios</h2>
            <div class="divider"></div>
            <p>Diseñado específicamente para optimizar la logística, facturación y control diario de hidrocarburos.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Control de Inventario</h3>
                <p>Monitoreo en tiempo real del stock de tanques. El sistema descuenta automáticamente los litros vendidos y previene desabastecimientos.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Despacho Automatizado</h3>
                <p>Interfaz rápida y optimizada para operadores (grisferos). Ingresa litros o soles y calcula equivalencias de inmediato en base al precio actual.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Auditoría de Turnos</h3>
                <p>Cierre de turnos de operarios ingresando lecturas acumuladas finales de las mangueras. Compara ventas registradas contra la caja física.</p>
            </div>
        </div>
    </section>

    <!-- Sección de Seguridad / Footer -->
    <footer id="seguridad" class="footer">
        <div class="footer-container">
            <p>&copy; 2026 PetroAdmin. Todos los derechos reservados. Sistema Web Corporativo para la Operación de Grifos.</p>
            <p class="footer-subtext">Diseñado bajo estándares de seguridad informática y control de inventarios de hidrocarburos.</p>
        </div>
    </footer>

</body>
</html>
