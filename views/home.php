<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOSPERÚ — Control Inteligente de Combustibles</title>
    <!-- Importación de Outfit e Inter para Akkurat, y JetBrains Mono para fragmentMono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=JetBrains+Mono:wght@400;500&family=Outfit:wght@400;500;700&display=swap"
        rel="stylesheet">
    <!-- CSS Modular Adaline -->
    <link rel="stylesheet" href="assets/css/home.css">
    <!-- Identidad de Marca: Favicon para Pestañas del Navegador -->
    <link rel="icon" type="image/png" href="assets/images/icon.png">
</head>

<body>

    <!-- CABECERA (3 COLUMNAS ADALINE SYSTEM) -->
    <header class="navbar">
        <div class="navbar-container">

            <!-- Columna Izquierda: Enlaces a secciones con Anclaje Smooth Scroll -->
            <div class="nav-left">
                <a href="#caracteristicas" class="nav-item">OPERACIONES</a>
                <a href="#tarifas" class="nav-item">TARIFAS</a>
                <a href="#seguridad" class="nav-item">SEGURIDAD</a>
            </div>

            <!-- Columna Central: Marca JOSPERÚn con Imagotipo Adaline -->
            <div class="nav-center">
                <a href="home" class="logo">
                    <!-- Icono de Hoja Adaline SVG -->
                    <svg class="logo-leaf-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12c0 3.65 1.95 6.84 4.88 8.61l.03-.03c.59-.59.95-1.4.95-2.3 0-1.79-1.46-3.25-3.25-3.25h-.06C4.2 13.9 4 12.97 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 .97-.2 1.9-.55 2.75l-.01.03c-.22.53-.55.99-.95 1.35l-.03.03c-.59.59-.95 1.4-.95 2.3 0 1.79 1.46 3.25 3.25 3.25.04 0 .07 0 .11-.01C19.78 18.06 21.6 15.22 21.6 12c0-5.52-4.48-10-10-10zm-1.25 15.5c0-.69.56-1.25 1.25-1.25s1.25.56 1.25 1.25-.56 1.25-1.25 1.25-1.25-.56-1.25-1.25z" />
                    </svg>
                    <span class="logo-text">JOSPERÚ</span>
                </a>
            </div>

            <!-- Columna Derecha: Botones Funcionales -->
            <div class="nav-right">
                <button type="button" class="btn-watch-demo" onclick="openDemoModal()">
                    SIMULADOR
                    <span class="play-circle-icon">
                        <svg class="play-arrow-svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </span>
                </button>
                <a href="login" class="btn-start-free">ACCESO PERSONAL</a>
            </div>

        </div>
    </header>

    <!-- SECCIÓN HERO -->
    <section class="hero-section-replica">
        <div class="hero-content-replica">

            <!-- Gran titular centrado sobre el Grifo -->
            <h1 class="hero-main-headline">
                La plataforma única para controlar,<br>
                calcular, despachar y auditar combustibles
            </h1>

            <!-- Sección "TRUSTED BY" con Marquesina Infinita Rotativa -->
            <div class="trusted-by-container">
                <span class="trusted-by-text">VALORES DE OPERACIÓN</span>
                <div class="marquee-wrapper">
                    <div class="marquee-content font-mono">
                        <span>CONFIANZA</span>
                        <span class="bullet">•</span>
                        <span>RESPETO</span>
                        <span class="bullet">•</span>
                        <span>SEGURIDAD</span>
                        <span class="bullet">•</span>
                        <span>INTEGRIDAD</span>
                        <span class="bullet">•</span>
                        <span>CALIDAD</span>
                        <span class="bullet">•</span>
                        <span>EFICIENCIA</span>
                        <span class="bullet">•</span>
                        <!-- Duplicado para loop sin fin perfecto -->
                        <span>CONFIANZA</span>
                        <span class="bullet">•</span>
                        <span>RESPETO</span>
                        <span class="bullet">•</span>
                        <span>SEGURIDAD</span>
                        <span class="bullet">•</span>
                        <span>INTEGRIDAD</span>
                        <span class="bullet">•</span>
                        <span>CALIDAD</span>
                        <span class="bullet">•</span>
                        <span>EFICIENCIA</span>
                        <span class="bullet">•</span>
                    </div>
                </div>
            </div>

            <!-- Gran Paisaje Widescreen de Fondo de Adaline -->
            <div class="full-bleed-landscape-wrapper">
                <div class="landscape-canvas" style="background-image: url('assets/images/carro.png');"></div>
            </div>

        </div>
    </section>

    <!-- SECCIÓN DE CARACTERÍSTICAS -->
    <section id="caracteristicas" class="features-section">
        <div class="section-title">
            <span class="technical-tag">MÓDULOS DE CONTROL CENTRAL</span>
            <h2>Servicios y Logística del Grifo</h2>
        </div>

        <div class="features-grid">

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Mód-01</span>
                <h3>Control de Inventario</h3>
                <p>Auditoría milimétrica del stock en tanques de almacenamiento. La base de datos realiza reducciones
                    matemáticas instantáneas previniendo variaciones de almacenamiento.</p>
            </article>

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Mód-02</span>
                <h3>Caja del Operador</h3>
                <p>Interfaz limpia de ventas y mangueras para griferos. Cálculo ágil de equivalencias de soles y litros
                    bajo baja carga cognitiva.</p>
            </article>

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Mód-03</span>
                <h3>Cierres Financieros</h3>
                <p>Cuadre de caja automatizado al finalizar turnos. Compara la declaración de caja física contra
                    acumuladores mecánicos de manguera.</p>
            </article>

        </div>
    </section>

    <!-- SECCIÓN DE TARIFAS (NUEVA SECCIÓN TEMÁTICA ADALINE) -->
    <section id="tarifas" class="features-section">
        <div class="section-title">
            <span class="technical-tag">PRECIOS OPERATIVOS DEL DÍA</span>
            <h2>Tarifas de Hidrocarburos</h2>
        </div>

        <div class="prices-container">
            <div class="price-box">
                <span class="fuel-type">Gasolina 95 Oct</span>
                <span class="fuel-price font-mono">S/ 22.14 / G</span>
                <span class="fuel-metric font-mono">Equivale a S/ 5.85 por Litro</span>
            </div>
            <div class="price-box">
                <span class="fuel-type">Gasolina 90 Oct</span>
                <span class="fuel-price font-mono">S/ 19.30 / G</span>
                <span class="fuel-metric font-mono">Equivale a S/ 5.10 por Litro</span>
            </div>
            <div class="price-box">
                <span class="fuel-type">Diesel (Petróleo)</span>
                <span class="fuel-price font-mono">S/ 19.68 / G</span>
                <span class="fuel-metric font-mono">Equivale a S/ 5.20 por Litro</span>
            </div>
        </div>
    </section>

    <!-- SECCIÓN DE SEGURIDAD Y COMPLIANCE (NUEVO PROTOCOLO JOSPERÚ) -->
    <section id="seguridad" class="features-section" style="border-top: 1px solid var(--color-stone-moss); padding-top: var(--spacing-64); margin-top: var(--spacing-32);">
        <div class="section-title">
            <span class="technical-tag">PROTOCOLOS DE PREVENCIÓN DE RIESGOS</span>
            <h2>Seguridad y Compliance Operativo</h2>
        </div>

        <div class="features-grid">

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Prot-01</span>
                <h3>Cifrado de Credenciales</h3>
                <p>Las contraseñas de todos los griferos y del administrador están resguardadas de extremo a extremo en la base de datos mediante algoritmos criptográficos <strong>Bcrypt</strong> unidireccionales de alta seguridad.</p>
            </article>

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Prot-02</span>
                <h3>Integridad Transaccional</h3>
                <p>Monitoreo y consistencia SQL. Ninguna venta puede anularse o alterarse en reportes sin reajustar con precisión matemática el inventario de combustibles en tanques de almacenamiento y totalizadores de surtidor.</p>
            </article>

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Prot-03</span>
                <h3>Aislamiento de Roles</h3>
                <p>Segregación estricta de funciones. El personal de manguera tiene acceso exclusivo al panel físico de los surtidores, mientras que la auditoría financiera, boletas y creación de usuarios son exclusivas del administrador.</p>
            </article>

        </div>
    </section>

    <!-- PIE DE PÁGINA -->
    <footer class="footer-adaline">
        <div class="footer-container">
            <p class="brand-text">JOSPERÚ</p>
            <p class="copyright">&copy; 2026. Todos los derechos reservados. Estación de Control Central.</p>
            <p class="compliance font-mono">Compliance Normativo Hidrocarburos v4.2</p>
        </div>
    </footer>

    <!-- MODAL DE DEMO EN VIVO (LIGHTBOX INTERACTIVO ADALINE) -->
    <div id="demoModal" class="demo-modal-overlay">
        <div class="demo-modal-card">
            <header class="demo-modal-header">
                <h3>Simulador Despachador de Combustible</h3>
                <button type="button" class="btn-close-modal" onclick="closeDemoModal()">&times;</button>
            </header>
            <div class="demo-modal-body">
                <p class="demo-desc">Selecciona el tipo de combustible e introduce la cantidad en galones para proyectar el costo en tiempo real.</p>

                <div class="demo-simulator-box">
                    <div class="sim-group">
                        <label for="demoCombustible" class="font-mono">TIPO DE COMBUSTIBLE</label>
                        <select id="demoCombustible" onchange="actualizarPrecioCombustible()">
                            <option value="22.14" data-stock="5000">Gasolina 95 — S/ 22.14 por Galón</option>
                            <option value="19.30" data-stock="3000">Gasolina 90 — S/ 19.30 por Galón</option>
                            <option value="19.68" data-stock="8000">Diesel — S/ 19.68 por Galón</option>
                        </select>
                    </div>
                    <div class="sim-group">
                        <label for="demoLitros" class="font-mono">CANTIDAD EN GALONES</label>
                        <input type="number" id="demoLitros" placeholder="0.00" oninput="calcularDemoTotal()" min="0">
                        <span id="demoStockLabel" class="font-mono" style="font-size: 11px; margin-top: 4px; display: block; color: var(--color-valley-green);">Stock Disponible: 5000 Galones</span>
                    </div>
                    <div class="sim-divider"></div>
                    <div class="sim-result">
                        <span class="lbl font-mono">TOTAL PROYECTADO</span>
                        <span class="val font-mono" id="demoTotal">S/ 0.00</span>
                    </div>
                </div>
            </div>
            <footer class="demo-modal-footer">
                <a href="login" class="btn-demo-primary">Acceder al Sistema Completo</a>
            </footer>
        </div>
    </div>

    <!-- JS PARA INTERACTIVIDAD Y CÁLCULOS EN VIVO -->
    <script src="assets/js/home.js"></script>

</body>

</html>