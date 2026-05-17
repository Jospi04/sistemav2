<?php
namespace Controller;

use Model\Usuario;

class AuthController {
    /**
     * Procesa el inicio de sesión o muestra la vista correspondiente.
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($usuario) || empty($password)) {
                $_SESSION['error'] = 'Por favor complete todos los campos.';
                header('Location: login');
                exit;
            }

            // Llamar al modelo para validar credenciales
            $user = Usuario::login($usuario, $password);

            if ($user) {
                // Registrar datos del usuario en sesión
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['rol'] = $user['rol'];

                // Redirección inteligente según rol
                if ($user['rol'] === 'admin') {
                    header('Location: dashboard');
                } else {
                    header('Location: ventas');
                }
                exit;
            } else {
                $_SESSION['error'] = 'Nombre de usuario o contraseña incorrectos.';
                header('Location: login');
                exit;
            }
        } else {
            // Cargar la vista de login mediante el router
            require_once BASE_DIR . '/views/login.php';
        }
    }

    /**
     * Destruye la sesión del usuario.
     */
    public function logout() {
        session_destroy();
        header('Location: login');
        exit;
    }
}
