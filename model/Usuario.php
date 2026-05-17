<?php
namespace Model;

use Config\Database;
use PDO;

class Usuario {
    /**
     * Valida las credenciales de un usuario contra la base de datos.
     * 
     * @param string $usuario Nombre de usuario ingresado.
     * @param string $password Contraseña en texto plano.
     * @return array|false Retorna el registro del usuario si es correcto, o false si falla.
     */
    public static function login($usuario, $password) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1");
        $stmt->execute([':usuario' => $usuario]);
        $user = $stmt->fetch();

        // Verificar hash bcrypt almacenado
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
}
