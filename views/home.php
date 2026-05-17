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
    <link rel="stylesheet" href="assets/home.css">
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
                    VER DEMO
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
                <div class="landscape-canvas" style="background-image: url('assets/adaline_landscape.png');"></div>
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
                <span class="fuel-price font-mono">S/ 18.50</span>
                <span class="fuel-metric font-mono">Por Galón</span>
            </div>
            <div class="price-box">
                <span class="fuel-type">Gasolina 90 Oct</span>
                <span class="fuel-price font-mono">S/ 16.20</span>
                <span class="fuel-metric font-mono">Por Galón</span>
            </div>
            <div class="price-box">
                <span class="fuel-type">Diesel B5 S-50</span>
                <span class="fuel-price font-mono">S/ 15.80</span>
                <span class="fuel-metric font-mono">Por Galón</span>
            </div>
        </div>
    </section>

    <!-- PIE DE PÁGINA -->
    <footer id="seguridad" class="footer-adaline">
        <div class="footer-container">
            <p class="brand-text">JOSPERÚN</p>
            <p class="copyright">&copy; 2026. Todos los derechos reservados. Estación de Control Central.</p>
            <p class="compliance font-mono">Compliance Normativo Hidrocarburos v4.2</p>
        </div>
    </footer>

    <!-- MODAL DE DEMO EN VIVO (LIGHTBOX INTERACTIVO ADALINE) -->
    <div id="demoModal" class="demo-modal-overlay">
        <div class="demo-modal-card">
            <header class="demo-modal-header">
                <h3>Simulador Despachador en Vivo</h3>
                <button type="button" class="btn-close-modal" onclick="closeDemoModal()">&times;</button>
            </header>
            <div class="demo-modal-body">
                <p class="demo-desc">Introduce la cantidad de litros para calcular el costo proyectado en base a
                    Gasolina 95 Oct (S/ 4.88 por Litro).</p>

                <div class="demo-simulator-box">
                    <div class="sim-group">
                        <label for="demoLitros" class="font-mono">CANTIDAD EN LITROS</label>
                        <input type="number" id="demoLitros" placeholder="0.00" oninput="calcularDemoTotal()" min="0">
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
    <script>
        // Funciones del Modal de Demo
        function openDemoModal() {
            document.getElementById('demoModal').classList.add('active');
            document.body.style.overflow = 'hidden'; // Detener scroll de fondo
        }

        function closeDemoModal() {
            document.getElementById('demoModal').classList.remove('active');
            document.body.style.overflow = 'auto'; // Restaurar scroll
        }

        function calcularDemoTotal() {
            const litrosInput = document.getElementById('demoLitros');
            const totalSpan = document.getElementById('demoTotal');
            const precioPorLitro = 4.88; // Aprox S/ 18.50 por galón (3.785 L)

            const litros = parseFloat(litrosInput.value);
            if (isNaN(litros) || litros <= 0) {
                totalSpan.textContent = 'S/ 0.00';
            } else {
                const total = litros * precioPorLitro;
                totalSpan.textContent = 'S/ ' + total.toFixed(2);
            }
        }

        // Cerrar modal al hacer clic en el fondo grisáceo
        window.onclick = function (event) {
            const modal = document.getElementById('demoModal');
            if (event.target == modal) {
                closeDemoModal();
            }
        }
    </script>

</body>

</html>