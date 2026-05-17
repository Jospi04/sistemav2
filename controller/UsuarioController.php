<?php
namespace Controller;

use Config\Database;
use PDO;

class UsuarioController {
    /**
     * Muestra la lista de usuarios y el formulario de registro.
     */
    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: login');
            exit;
        }

        // Bloqueo estricto de seguridad: Solo administradores
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: ventas');
            exit;
        }

        $db = Database::getConnection();

        // Obtener todos los usuarios
        $stmt = $db->query("SELECT id, nombre, usuario, rol FROM usuarios ORDER BY nombre ASC");
        $usuariosList = $stmt->fetchAll();

        // Parámetros de renderizado
        $activePage = 'usuarios';
        $pageTitle = 'Gestionar Vendedores y Griferos';
        $extraCss = 'usuarios.css';
        $extraJs = 'usuarios.js';
        $viewFile = 'usuarios.php';

        require_once BASE_DIR . '/views/layout.php';
    }

    /**
     * Registra un nuevo usuario en la base de datos.
     */
    public function crear() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $usuario = trim($_POST['usuario'] ?? '');
            $password = $_POST['password'] ?? '';
            $rol = $_POST['rol'] ?? 'operario';

            if (empty($nombre) || empty($usuario) || empty($password)) {
                $_SESSION['error'] = 'Por favor, complete todos los campos obligatorios.';
                header('Location: /usuarios');
                exit;
            }

            $db = Database::getConnection();

            // Verificar si el usuario ya existe
            $stmtCheck = $db->prepare("SELECT id FROM usuarios WHERE usuario = ?");
            $stmtCheck->execute([$usuario]);
            if ($stmtCheck->fetch()) {
                $_SESSION['error'] = 'El nombre de usuario "' . htmlspecialchars($usuario) . '" ya está registrado.';
                header('Location: /usuarios');
                exit;
            }

            // Encriptar contraseña de forma segura con BCRYPT
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            // Insertar usuario
            $stmtInsert = $db->prepare("INSERT INTO usuarios (nombre, usuario, password, rol) VALUES (?, ?, ?, ?)");
            if ($stmtInsert->execute([$nombre, $usuario, $passwordHash, $rol])) {
                $_SESSION['success'] = '¡Usuario "' . htmlspecialchars($nombre) . '" creado con éxito!';
            } else {
                $_SESSION['error'] = 'Error al registrar el usuario en la base de datos.';
            }

            header('Location: /usuarios');
            exit;
        }
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function eliminar() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: login');
            exit;
        }

        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID de usuario no válido.';
            header('Location: /usuarios');
            exit;
        }

        // Impedir que un administrador se elimine a sí mismo
        if ($id === intval($_SESSION['usuario_id'])) {
            $_SESSION['error'] = 'No puedes eliminar tu propia cuenta de administrador.';
            header('Location: /usuarios');
            exit;
        }

        $db = Database::getConnection();

        // Eliminar usuario
        $stmtDelete = $db->prepare("DELETE FROM usuarios WHERE id = ?");
        if ($stmtDelete->execute([$id])) {
            $_SESSION['success'] = '¡Usuario eliminado correctamente!';
        } else {
            $_SESSION['error'] = 'Error al eliminar el usuario.';
        }

        header('Location: /usuarios');
        exit;
    }
}
