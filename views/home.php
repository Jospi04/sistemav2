<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brosteria 24/7 — El sabor más crujiente a cualquier hora</title>
    <!-- Importación de Outfit e Inter para Akkurat, y JetBrains Mono para fragmentMono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=JetBrains+Mono:wght@400;500&family=Outfit:wght@400;500;700;800&display=swap" rel="stylesheet">
    <!-- Boxicons CDN para Iconos -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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
                <a href="#caracteristicas" class="nav-item">SERVICIOS</a>
                <a href="#tarifas" class="nav-item">NUESTRO MENÚ</a>
                <a href="#seguridad" class="nav-item">SEGURIDAD</a>
            </div>

            <!-- Columna Central: Marca Brosteria 24/7 con Imagotipo -->
            <div class="nav-center">
                <a href="home" class="logo">
                    <i class='bx bx-restaurant' style="color: #f59e0b; font-size: 22px; margin-right: 8px;"></i>
                    <span class="logo-text" style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 20px;">Brosteria 24/7</span>
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
                <a href="login" class="btn-start-free">INICIAR SESIÓN</a>
            </div>

        </div>
    </header>

    <!-- SECCIÓN HERO -->
    <section class="hero-section-replica">
        <div class="hero-content-replica">

            <!-- Gran titular centrado sobre la Brostería -->
            <h1 class="hero-main-headline" style="font-family: 'Outfit', sans-serif; font-weight: 800;">
                El sabor crujiente y sabroso<br>
                que te acompaña las 24 horas del día
            </h1>

            <!-- Sección "TRUSTED BY" con Marquesina Infinita Rotativa -->
            <div class="trusted-by-container">
                <span class="trusted-by-text">NUESTROS VALORES</span>
                <div class="marquee-wrapper">
                    <div class="marquee-content font-mono">
                        <span>CRUJIENTE</span>
                        <span class="bullet">•</span>
                        <span>SABOR ÚNICO</span>
                        <span class="bullet">•</span>
                        <span>HIGIENE</span>
                        <span class="bullet">•</span>
                        <span>RAPIDEZ</span>
                        <span class="bullet">•</span>
                        <span>CALIDAD</span>
                        <span class="bullet">•</span>
                        <span>PASIÓN</span>
                        <span class="bullet">•</span>
                        <!-- Duplicado para loop sin fin perfecto -->
                        <span>CRUJIENTE</span>
                        <span class="bullet">•</span>
                        <span>SABOR ÚNICO</span>
                        <span class="bullet">•</span>
                        <span>HIGIENE</span>
                        <span class="bullet">•</span>
                        <span>RAPIDEZ</span>
                        <span class="bullet">•</span>
                        <span>CALIDAD</span>
                        <span class="bullet">•</span>
                        <span>PASIÓN</span>
                        <span class="bullet">•</span>
                    </div>
                </div>
            </div>

            <!-- Gran Paisaje Widescreen de Fondo de Adaline (Cargando broster.png) -->
            <div class="full-bleed-landscape-wrapper">
                <div class="landscape-canvas" style="background-image: url('assets/images/broster.png');"></div>
            </div>

        </div>
    </section>

    <!-- SECCIÓN DE CARACTERÍSTICAS -->
    <section id="caracteristicas" class="features-section">
        <div class="section-title">
            <span class="technical-tag">MÓDULOS DE CONTROL CENTRAL</span>
            <h2>Servicios y Logística de la Brostería</h2>
        </div>

        <div class="features-grid">

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Mód-01</span>
                <h3>Control de Insumos</h3>
                <p>Auditoría milimétrica del stock de presas de pollo, porciones de papas y bebidas. El sistema descuenta de forma automatizada con cada venta concretada.</p>
            </article>

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Mód-02</span>
                <h3>Caja Registradora</h3>
                <p>Interfaz intuitiva para cajeros y personal de atención. Registro de pedidos ágil indicando cantidad de porciones, número de mesa y método de pago.</p>
            </article>

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Mód-03</span>
                <h3>Reportes e Historiales</h3>
                <p>Cuadre de caja inmediato al finalizar la jornada. Permite auditar ingresos del día, comparar ventas por combos y verificar stock restante en tiempo real.</p>
            </article>

        </div>
    </section>

    <!-- SECCIÓN DE TARIFAS -->
    <section id="tarifas" class="features-section">
        <div class="section-title">
            <span class="technical-tag">COMBOS MÁS SOLICITADOS</span>
            <h2>Nuestros Precios del Día</h2>
        </div>

        <div class="prices-container">
            <div class="price-box">
                <span class="fuel-type">Combo Mostrito Broster</span>
                <span class="fuel-price font-mono">S/ 18.50 / Und</span>
                <span class="fuel-metric font-mono">Pollo crujiente + arroz chaufa + papas</span>
            </div>
            <div class="price-box">
                <span class="fuel-type">Broster Clásico Personal</span>
                <span class="fuel-price font-mono">S/ 14.50 / Und</span>
                <span class="fuel-metric font-mono">1 Presa + porción de papas fritas</span>
            </div>
            <div class="price-box">
                <span class="fuel-type">Salchipapa Especial</span>
                <span class="fuel-price font-mono">S/ 13.50 / Und</span>
                <span class="fuel-metric font-mono">Salchicha premium + papas crocantes</span>
            </div>
        </div>
    </section>

    <!-- SECCIÓN DE SEGURIDAD -->
    <section id="seguridad" class="features-section" style="border-top: 1px solid var(--color-stone-moss); padding-top: var(--spacing-64); margin-top: var(--spacing-32);">
        <div class="section-title">
            <span class="technical-tag">PROTOCOLOS DE CONTROL DE CALIDAD</span>
            <h2>Seguridad e Integridad del Sistema</h2>
        </div>

        <div class="features-grid">

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Prot-01</span>
                <h3>Cifrado de Credenciales</h3>
                <p>El acceso de cajeros, administradores y personal de cocina está protegido de extremo a extremo en la base de datos utilizando el cifrado seguro <strong>Bcrypt</strong>.</p>
            </article>

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Prot-02</span>
                <h3>Integridad de Inventario</h3>
                <p>Consistencia de stock garantizada. Al anular un pedido en el panel, el sistema devuelve automáticamente las porciones y los insumos correspondientes al almacén físico.</p>
            </article>

            <article class="feature-card">
                <span class="card-mono-badge font-mono">Prot-03</span>
                <h3>Segregación de Roles</h3>
                <p>Administradores gestionan reportes consolidados y creación de usuarios, mientras que el personal operativo dispone de acceso exclusivo para registrar ventas y comandas.</p>
            </article>

        </div>
    </section>

    <!-- PIE DE PÁGINA -->
    <footer class="footer-adaline">
        <div class="footer-container">
            <p class="brand-text" style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 24px; color: #ffc107;">Brosteria 24/7</p>
            <p class="copyright">&copy; 2026. Todos los derechos reservados. Brosteria 24/7 Central.</p>
            <p class="compliance font-mono">Sistema de Gestión e Insumos de Alimentos v2.1</p>
        </div>
    </footer>

    <!-- MODAL DE DEMO EN VIVO (LIGHTBOX INTERACTIVO ADALINE) -->
    <div id="demoModal" class="demo-modal-overlay">
        <div class="demo-modal-card">
            <header class="demo-modal-header">
                <h3>Simulador de Pedido Brostería</h3>
                <button type="button" class="btn-close-modal" onclick="closeDemoModal()">&times;</button>
            </header>
            <div class="demo-modal-body">
                <p class="demo-desc">Selecciona el combo de comida rápida de la brostería y la cantidad para simular el costo del pedido en tiempo real.</p>

                <div class="demo-simulator-box">
                    <div class="sim-group">
                        <label for="demoCombustible" class="font-mono">COMBO / OPCIÓN MENÚ</label>
                        <select id="demoCombustible" onchange="actualizarPrecioCombustible()">
                            <option value="18.50" data-stock="150">Combo Mostrito Broster — S/ 18.50</option>
                            <option value="14.50" data-stock="200">Broster Clásico Personal — S/ 14.50</option>
                            <option value="13.50" data-stock="180">Salchipapa Especial — S/ 13.50</option>
                            <option value="16.00" data-stock="120">Alitas BBQ x6 + Papas — S/ 16.00</option>
                            <option value="4.50" data-stock="500">Gaseosa 500ml — S/ 4.50</option>
                        </select>
                    </div>
                    <div class="sim-group">
                        <label for="demoLitros" class="font-mono">CANTIDAD DE PORCIONES</label>
                        <input type="number" id="demoLitros" placeholder="0" oninput="calcularDemoTotal()" min="1" step="1" value="1">
                        <span id="demoStockLabel" class="font-mono" style="font-size: 11px; margin-top: 4px; display: block; color: var(--color-valley-green);">Stock Disponible: 150 Porciones</span>
                    </div>
                    <div class="sim-divider"></div>
                    <div class="sim-result">
                        <span class="lbl font-mono">TOTAL CALCULADO</span>
                        <span class="val font-mono" id="demoTotal">S/ 18.50</span>
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