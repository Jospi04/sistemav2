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
        // Página de error 404 corporativa sutil
        http_response_code(404);
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>404 - No Encontrado</title>
            <style>
                body {
                    background-color: #0F172A;
                    color: #F8FAFC;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .container {
                    text-align: center;
                    border: 1px solid #334155;
                    padding: 40px;
                    border-radius: 8px;
                    background-color: #1E293B;
                    max-width: 500px;
                }
                h1 { color: #EF4444; font-size: 2.5rem; margin-top: 0; }
                p { color: #94A3B8; margin-bottom: 20px; }
                a {
                    color: #0EA5E9;
                    text-decoration: none;
                    font-weight: bold;
                    border: 1px solid #0EA5E9;
                    padding: 10px 20px;
                    border-radius: 4px;
                    transition: all 0.3s ease;
                }
                a:hover { background-color: #0EA5E9; color: #FFFFFF; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>404</h1>
                <p>La página que estás buscando no existe o ha sido movida.</p>
                <a href='./home'>Volver al Inicio</a>
            </div>
        </body>
        </html>";
        break;
}
