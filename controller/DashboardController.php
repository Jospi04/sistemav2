<?php
namespace Controller;

use Config\Database;
use PDO;

class DashboardController {
    /**
     * Carga las estadísticas y el estado de la estación de servicio.
     */
    public function index() {
        // Validar sesión activa
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: login');
            exit;
        }

        // Restricción estricta de seguridad: Solo administradores entran al panel financiero
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: ventas');
            exit;
        }

        $db = Database::getConnection();

        // 1. Obtener KPIs principales del día de hoy
        $queryKPI = "SELECT 
                        COALESCE(SUM(total), 0) as total_dinero,
                        COALESCE(SUM(litros), 0) as total_litros,
                        COUNT(id) as transacciones
                     FROM ventas 
                     WHERE DATE(fecha) = CURDATE()";
        $kpis = $db->query($queryKPI)->fetch();

        // 2. Obtener niveles de stock de tanques físicos
        $queryTanks = "SELECT i.*, c.nombre as combustible_nombre 
                       FROM inventario i
                       JOIN combustibles c ON i.combustible_id = c.id
                       ORDER BY i.id ASC";
        $tanks = $db->query($queryTanks)->fetchAll();

        // 3. Obtener historial de los últimos 5 despachos para auditoría
        $queryRecent = "SELECT v.*, c.nombre as combustible_nombre, u.nombre as usuario_nombre
                        FROM ventas v
                        JOIN combustibles c ON v.combustible_id = c.id
                        JOIN usuarios u ON v.usuario_id = u.id
                        ORDER BY v.id DESC
                        LIMIT 5";
        $recentSales = $db->query($queryRecent)->fetchAll();

        // 4. Obtener historial de los últimos 5 reabastecimientos de cisternas
        $queryRefills = "SELECT r.*, c.nombre as combustible_nombre, u.nombre as usuario_nombre
                         FROM reabastecimientos r
                         JOIN inventario i ON r.inventario_id = i.id
                         JOIN combustibles c ON i.combustible_id = c.id
                         JOIN usuarios u ON r.usuario_id = u.id
                         ORDER BY r.id DESC
                         LIMIT 5";
        $refillsList = $db->query($queryRefills)->fetchAll();

        // 5. Parámetros de renderizado en la plantilla
        $activePage = 'dashboard';
        $pageTitle = 'Panel de Estadísticas y Control';
        $extraCss = 'dashboard.css';
        $viewFile = 'dashboard.php';

        require_once BASE_DIR . '/views/layout.php';
    }

    /**
     * Procesa el reabastecimiento de combustible en un tanque.
     */
    public function reabastecer() {
        // Validar sesión activa
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: login');
            exit;
        }

        // Restricción estricta de seguridad: Solo administradores pueden reabastecer
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: ventas');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventario_id = intval($_POST['inventario_id'] ?? 0);
            $cantidad = floatval($_POST['cantidad'] ?? 0);

            if ($inventario_id <= 0 || $cantidad <= 0) {
                $_SESSION['error'] = 'Por favor, ingrese un tanque válido y una cantidad mayor a cero.';
                header('Location: dashboard');
                exit;
            }

            $db = Database::getConnection();

            // Buscar el tanque
            $stmt = $db->prepare("SELECT i.*, c.nombre as combustible_nombre 
                                  FROM inventario i
                                  JOIN combustibles c ON i.combustible_id = c.id
                                  WHERE i.id = ?");
            $stmt->execute([$inventario_id]);
            $tank = $stmt->fetch();

            if (!$tank) {
                $_SESSION['error'] = 'El tanque seleccionado no existe.';
                header('Location: dashboard');
                exit;
            }

            $nuevo_stock = floatval($tank['stock_actual']) + $cantidad;
            $capacidad_maxima = floatval($tank['capacidad_maxima']);

            // Validar que no exceda la capacidad física del tanque
            if ($nuevo_stock > $capacidad_maxima) {
                $_SESSION['error'] = 'La recarga excede la capacidad del tanque de ' . $tank['combustible_nombre'] . ' (' . number_format($capacidad_maxima, 2) . ' Gal).';
                header('Location: dashboard');
                exit;
            }

            // Actualizar stock del inventario
            $stmtUpdate = $db->prepare("UPDATE inventario SET stock_actual = ? WHERE id = ?");
            if ($stmtUpdate->execute([$nuevo_stock, $inventario_id])) {
                // Registrar el evento de reabastecimiento en el historial
                $stmtLog = $db->prepare("INSERT INTO reabastecimientos (inventario_id, cantidad, usuario_id) VALUES (?, ?, ?)");
                $stmtLog->execute([$inventario_id, $cantidad, $_SESSION['usuario_id']]);
                
                $_SESSION['success'] = "¡Tanque recargado con éxito! Se agregaron " . floatval($cantidad) . " Galones de " . $tank['combustible_nombre'] . " al stock.";
            } else {
                $_SESSION['error'] = 'Error al registrar el reabastecimiento en la base de datos.';
            }

            header('Location: dashboard');
            exit;
        }
    }

    /**
     * Genera la vista de reportes de ventas con filtros de fecha.
     */
    public function reportes() {
        // Validar sesión activa
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: login');
            exit;
        }

        // Restricción de seguridad: Solo administradores pueden ver reportes financieros
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: ventas');
            exit;
        }

        $db = Database::getConnection();

        // 1. Obtener período seleccionado y calcular rango de fechas
        $periodo = $_GET['periodo'] ?? 'semana';
        $desdeInput = $_GET['desde'] ?? '';
        $hastaInput = $_GET['hasta'] ?? '';

        switch ($periodo) {
            case 'hoy':
                $desde = date('Y-m-d 00:00:00');
                $hasta = date('Y-m-d 23:59:59');
                break;
            case 'mes':
                $desde = date('Y-m-01 00:00:00');
                $hasta = date('Y-m-d 23:59:59');
                break;
            case 'personalizado':
                $desde = !empty($desdeInput) ? $desdeInput . ' 00:00:00' : date('Y-m-d 00:00:00', strtotime('-7 days'));
                $hasta = !empty($hastaInput) ? $hastaInput . ' 23:59:59' : date('Y-m-d 23:59:59');
                break;
            case 'semana':
            default:
                $desde = date('Y-m-d 00:00:00', strtotime('-7 days'));
                $hasta = date('Y-m-d 23:59:59');
                break;
        }

        // 2. Obtener KPIs globales del período
        $stmtKpi = $db->prepare("SELECT 
                                    COALESCE(SUM(total), 0) as total_dinero, 
                                    COALESCE(SUM(litros), 0) as total_litros, 
                                    COUNT(id) as transacciones 
                                 FROM ventas 
                                 WHERE fecha >= ? AND fecha <= ?");
        $stmtKpi->execute([$desde, $hasta]);
        $kpis = $stmtKpi->fetch();

        // 3. Obtener ventas desglosadas por tipo de combustible en el período
        $stmtCombustibles = $db->prepare("SELECT 
                                            c.nombre as combustible_nombre, 
                                            COALESCE(SUM(v.total), 0) as total_combustible, 
                                            COALESCE(SUM(v.litros), 0) as litros_combustible, 
                                            COUNT(v.id) as transacciones_combustible 
                                          FROM combustibles c 
                                          LEFT JOIN ventas v ON v.combustible_id = c.id AND v.fecha >= ? AND v.fecha <= ? 
                                          GROUP BY c.id 
                                          ORDER BY total_combustible DESC");
        $stmtCombustibles->execute([$desde, $hasta]);
        $combustiblesReport = $stmtCombustibles->fetchAll();

        // 4. Obtener listado detallado de todas las ventas del período
        $stmtVentas = $db->prepare("SELECT 
                                        v.*, 
                                        c.nombre as combustible_nombre, 
                                        u.nombre as usuario_nombre 
                                    FROM ventas v 
                                    JOIN combustibles c ON v.combustible_id = c.id 
                                    JOIN usuarios u ON v.usuario_id = u.id 
                                    WHERE v.fecha >= ? AND v.fecha <= ? 
                                    ORDER BY v.id DESC");
        $stmtVentas->execute([$desde, $hasta]);
        $ventasReport = $stmtVentas->fetchAll();

        // 5. Parámetros de renderizado
        $activePage = 'reportes';
        $pageTitle = 'Reportes de Despacho y Ventas';
        $extraCss = 'dashboard.css'; // Reutilizamos estilos del dashboard (tablas, kpis, tarjetas)
        $viewFile = 'reportes.php';

        require_once BASE_DIR . '/views/layout.php';
    }
}
