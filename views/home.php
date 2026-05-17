<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adaline — PetroAdmin</title>
    <!-- Importación de Outfit e Inter para Akkurat, y JetBrains Mono para fragmentMono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=JetBrains+Mono:wght@400;500&family=Outfit:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- CSS Modular Adaline -->
    <link rel="stylesheet" href="assets/home.css">
</head>
<body>

    <!-- CABECERA (3 COLUMNAS IDÉNTICA A LA CAPTURA DE PANTALLA) -->
    <header class="navbar">
        <div class="navbar-container">
            
            <!-- Columna Izquierda: Enlaces -->
            <div class="nav-left">
                <div class="nav-dropdown">
                    <span class="nav-item">PRODUCTS <span class="chevron">∨</span></span>
                </div>
                <a href="#caracteristicas" class="nav-item">PRICING</a>
                <a href="#seguridad" class="nav-item">BLOG</a>
            </div>

            <!-- Columna Central: Logotipo Oficial Adaline -->
            <div class="nav-center">
                <a href="home" class="logo">
                    <!-- Icono de Hoja Adaline SVG -->
                    <svg class="logo-leaf-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12c0 3.65 1.95 6.84 4.88 8.61l.03-.03c.59-.59.95-1.4.95-2.3 0-1.79-1.46-3.25-3.25-3.25h-.06C4.2 13.9 4 12.97 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 .97-.2 1.9-.55 2.75l-.01.03c-.22.53-.55.99-.95 1.35l-.03.03c-.59.59-.95 1.4-.95 2.3 0 1.79 1.46 3.25 3.25 3.25.04 0 .07 0 .11-.01C19.78 18.06 21.6 15.22 21.6 12c0-5.52-4.48-10-10-10zm-1.25 15.5c0-.69.56-1.25 1.25-1.25s1.25.56 1.25 1.25-.56 1.25-1.25 1.25-1.25-.56-1.25-1.25z"/>
                    </svg>
                    <span class="logo-text">Adaline</span>
                </a>
            </div>

            <!-- Columna Derecha: Acciones Redondeadas -->
            <div class="nav-right">
                <a href="login" class="btn-watch-demo">
                    WATCH DEMO 
                    <span class="play-circle-icon">
                        <svg class="play-arrow-svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </span>
                </a>
                <a href="login" class="btn-start-free">START FOR FREE</a>
            </div>

        </div>
    </header>

    <!-- SECCIÓN HERO (CENTRADA E IMPACTANTE) -->
    <section class="hero-section-replica">
        <div class="hero-content-replica">
            
            <!-- Gran titular centrado -->
            <h1 class="hero-main-headline">
                The single platform to iterate,<br>
                evaluate, deploy, and monitor AI agents
            </h1>

            <!-- Sección "TRUSTED BY" -->
            <div class="trusted-by-container">
                <span class="trusted-by-text">TRUSTED BY</span>
                <div class="trusted-logos-row">
                    <!-- Coframe -->
                    <div class="brand-logo-item">
                        <svg class="brand-logo-svg" viewBox="0 0 120 30" fill="currentColor">
                            <text x="10" y="20" font-family="sans-serif" font-weight="bold" font-size="16">Coframe</text>
                        </svg>
                    </div>
                    <!-- DOORDASH -->
                    <div class="brand-logo-item">
                        <svg class="brand-logo-svg" viewBox="0 0 120 30" fill="currentColor">
                            <text x="10" y="20" font-family="sans-serif" font-weight="900" font-size="16" letter-spacing="1">DOORDASH</text>
                        </svg>
                    </div>
                    <!-- Giift -->
                    <div class="brand-logo-item">
                        <svg class="brand-logo-svg" viewBox="0 0 120 30" fill="currentColor">
                            <text x="10" y="20" font-family="sans-serif" font-weight="bold" font-size="16" letter-spacing="-0.5">Giift</text>
                        </svg>
                    </div>
                    <!-- HubSpot -->
                    <div class="brand-logo-item">
                        <svg class="brand-logo-svg" viewBox="0 0 120 30" fill="currentColor">
                            <text x="10" y="20" font-family="sans-serif" font-weight="bold" font-size="16">HubSpot</text>
                        </svg>
                    </div>
                    <!-- Reforge -->
                    <div class="brand-logo-item">
                        <svg class="brand-logo-svg" viewBox="0 0 120 30" fill="currentColor">
                            <text x="10" y="20" font-family="sans-serif" font-weight="bold" font-size="16">Reforge</text>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Gran Paisaje Full-Bleed (adaline_landscape.png) -->
            <div class="full-bleed-landscape-wrapper">
                <div class="landscape-canvas" style="background-image: url('assets/adaline_landscape.png');"></div>
            </div>

        </div>
    </section>

    <!-- CARACTERÍSTICAS INTEGRADAS (Adaline Style Reference) -->
    <section id="caracteristicas" class="features-section">
        <div class="section-title">
            <span class="technical-tag">Módulos de Control Operativo</span>
            <h2>Servicios y Auditoría</h2>
        </div>

        <div class="features-grid">
            
            <article class="feature-card">
                <span class="card-mono-badge font-mono">Mód-01</span>
                <h3>Control de Inventario</h3>
                <p>Auditoría en tiempo real del stock de tanques. La base de datos realiza reducciones matemáticas instantáneas previniendo variaciones de almacenamiento.</p>
            </article>

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Mód-02</span>
                <h3>Caja del Operador</h3>
                <p>Interfaz limpia de ventas y mangueras para griferos. Cálculo ágil de equivalencias de soles y litros bajo baja carga cognitiva.</p>
            </article>

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Mód-03</span>
                <h3>Cierres Financieros</h3>
                <p>Cuadre de caja automatizado al finalizar turnos. Compara la declaración de caja física contra acumuladores mecánicos de manguera.</p>
            </article>

        </div>
    </section>

    <!-- PIE DE PÁGINA -->
    <footer id="seguridad" class="footer-adaline">
        <div class="footer-container">
            <p class="brand-text">PETROADMIN</p>
            <p class="copyright">&copy; 2026. Todos los derechos reservados. Estación de Control Central.</p>
            <p class="compliance font-mono">Compliance Normativo Hidrocarburos v4.2</p>
        </div>
    </footer>

</body>
</html>
