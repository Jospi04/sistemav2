<?php
namespace Config;

use PDO;
use PDOException;

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            // Leer variables del entorno directamente (Docker) o desde archivo .env local
            $host = getenv('DB_HOST') ?: 'localhost';
            $db_name = getenv('DB_NAME') ?: 'sistema_grifo';
            $username = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASSWORD') ?: '';
            $port = getenv('DB_PORT') ?: '3306';

            $envPath = __DIR__ . '/../.env';
            if (file_exists($envPath)) {
                $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) continue;
                    $parts = explode('=', $line, 2);
                    if (count($parts) === 2) {
                        $name = trim($parts[0]);
                        $value = trim($parts[1]);
                        if (!getenv($name)) {
                            putenv("{$name}={$value}");
                            $_ENV[$name] = $value;
                        }
                    }
                }
                $host = getenv('DB_HOST') ?: $host;
                $db_name = getenv('DB_NAME') ?: $db_name;
                $username = getenv('DB_USER') ?: $username;
                $password = getenv('DB_PASSWORD') ?: $password;
                $port = getenv('DB_PORT') ?: $port;
            }

            try {
                $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
                self::$connection = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                die("Error de conexión a la base de datos: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
