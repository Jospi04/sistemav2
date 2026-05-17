<?php
/**
 * Controlador Frontal - Enrutador Principal
 */
session_start();

// Definir constante del directorio raíz de la aplicación
define('BASE_DIR', __DIR__);

// Registro de Autocarga (PSR-4 adaptado para case-sensitivity en Linux/Docker)
spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', '/', $class);
    
    // Convertir el nombre de la carpeta raíz del namespace a minúsculas (ej. Controller -> controller, Model -> model)
    $parts = explode('/', $classPath);
    if (count($parts) > 1) {
        $parts[0] = strtolower($parts[0]);
    }
    $classPath = implode('/', $parts);
    
    $file = BASE_DIR . '/' . ltrim($classPath, '/') . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Capturar parámetro 'url' provisto por .htaccess, limpiar espacios
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';

// Enrutamiento modular a vistas principales
switch ($url) {
    case '':
    case 'home':
        // Carga la landing page
        if (file_exists(BASE_DIR . '/views/home.php')) {
            require_once BASE_DIR . '/views/home.php';
        } else {
            echo "Bienvenido al Sistema de Grifo. Inicializando Fase 1 de Desarrollo.";
        }
        break;

    case 'login':
        $controller = new Controller\AuthController();
        $controller->login();
        break;

    case 'logout':
        $controller = new Controller\AuthController();
        $controller->logout();
        break;

    case 'dashboard':
        $controller = new Controller\DashboardController();
        $controller->index();
        break;

    case 'reportes':
        $controller = new Controller\DashboardController();
        $controller->reportes();
        break;

    case 'usuarios':
        $controller = new Controller\UsuarioController();
        $controller->index();
        break;

    case 'usuarios/crear':
        $controller = new Controller\UsuarioController();
        $controller->crear();
        break;

    case 'usuarios/eliminar':
        $controller = new Controller\UsuarioController();
        $controller->eliminar();
        break;

    case 'reabastecer':
        $controller = new Controller\DashboardController();
        $controller->reabastecer();
        break;

    case 'ventas':
        $controller = new Controller\VentasController();
        $controller->registrar();
        break;

    case 'boleta':
        $controller = new Controller\VentasController();
        $controller->verBoleta();
        break;

    default:
        // Página de error 404 corporativa de lujo (Adaline Canvas Ice)
        http_response_code(404);
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>404 - No Encontrado</title>
            <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap' rel='stylesheet'>
            <style>
                :root {
                    --bg-page: #fbfdf6;
                    --text-title: #2c3e29;
                    --text-body: #606d5c;
                    --accent-color: #556c4d;
                    --border-color: #e5eadf;
                    --bg-card: #ffffff;
                }
                body {
                    background-color: var(--bg-page);
                    color: var(--text-body);
                    font-family: 'Inter', sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .container {
                    text-align: center;
                    border: 1px solid var(--border-color);
                    padding: 48px 40px;
                    border-radius: 24px;
                    background-color: var(--bg-card);
                    max-width: 440px;
                    box-shadow: rgba(0, 0, 0, 0.03) 0px 10px 30px;
                    box-sizing: border-box;
                }
                h1 { 
                    color: #ef4444; 
                    font-size: 3.5rem; 
                    margin: 0 0 10px 0; 
                    font-weight: 800;
                    letter-spacing: -0.04em;
                }
                p { 
                    color: var(--text-body); 
                    margin: 0 0 28px 0; 
                    font-size: 0.95rem;
                    line-height: 1.6;
                }
                a {
                    color: var(--bg-card);
                    background-color: var(--accent-color);
                    text-decoration: none;
                    font-weight: bold;
                    font-size: 0.88rem;
                    padding: 12px 28px;
                    border-radius: 10px;
                    display: inline-block;
                    transition: all 0.25s ease;
                }
                a:hover { 
                    opacity: 0.92;
                    transform: translateY(-1px);
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>404</h1>
                <p>La página que estás buscando no existe o ha sido movida.</p>
                <a href='/home'>Volver al Inicio</a>
            </div>
        </body>
        </html>";
        break;
}
