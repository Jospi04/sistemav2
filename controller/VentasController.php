<?php
namespace Controller;

use Model\Venta;

class VentasController {
    /**
     * Muestra la interfaz de despacho o procesa el registro de la venta.
     */
    public function registrar() {
        // Asegurar que el usuario está logueado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: login');
            exit;
        }

        // Obtener surtidores para rellenar el formulario dinámicamente
        $surtidores = Venta::getSurtidoresActivos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $surtidor_id = intval($_POST['surtidor_id'] ?? 0);
            $litros = floatval($_POST['litros'] ?? 0);
            $placa = trim($_POST['placa_vehiculo'] ?? '');
            $metodo_pago = trim($_POST['metodo_pago'] ?? '');

            // Validación básica
            if ($surtidor_id <= 0 || $litros <= 0 || empty($metodo_pago)) {
                $_SESSION['error'] = 'Por favor, complete todos los campos obligatorios del despacho.';
                header('Location: ventas');
                exit;
            }

            // Buscar el surtidor en la lista para obtener el combustible y precio unitario reales
            $surtidorSeleccionado = null;
            foreach ($surtidores as $s) {
                if (intval($s['id']) === $surtidor_id) {
                    $surtidorSeleccionado = $s;
                    break;
                }
            }

            if (!$surtidorSeleccionado) {
                $_SESSION['error'] = 'El surtidor seleccionado no se encuentra activo o no existe.';
                header('Location: ventas');
                exit;
            }

            $combustible_id = $surtidorSeleccionado['combustible_id'];
            $precio_litro = floatval($surtidorSeleccionado['precio_litro']);

            // ==========================================
            // LÓGICA CORE: Cálculo del total en el Controller
            // ==========================================
            $total = round($litros * $precio_litro, 2);

            // Guardar en base de datos llamando al modelo
            $usuario_id = $_SESSION['usuario_id'];
            $venta_id = Venta::guardar($usuario_id, $surtidor_id, $combustible_id, $litros, $precio_litro, $total, $placa, $metodo_pago);

            if ($venta_id) {
                $_SESSION['success'] = "¡Despacho registrado correctamente! Total: S/. " . number_format($total, 2) . " por " . floatval($litros) . " Gal de " . $surtidorSeleccionado['combustible_nombre'];
                $_SESSION['last_venta_id'] = $venta_id; // Reservado para emitir boleta en la Fase 7
                header('Location: ventas');
                exit;
            } else {
                $_SESSION['error'] = 'Error al registrar la transacción en la base de datos.';
                header('Location: ventas');
                exit;
            }
        } else {
            // Cargar la vista encapsulada en el layout maestro
            $activePage = 'ventas';
            $pageTitle = 'Registro de Despacho';
            $extraCss = 'ventas.css';
            $viewFile = 'ventas.php';
            require_once BASE_DIR . '/views/layout.php';
        }
    }

    /**
     * Muestra el comprobante de boleta de la última venta o de una específica.
     */
    public function verBoleta() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: login');
            exit;
        }

        // Buscar ID de venta (desde parámetro GET o desde la última registrada en la sesión)
        $venta_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_SESSION['last_venta_id']) ? intval($_SESSION['last_venta_id']) : 0);

        $ventaDetalle = null;
        if ($venta_id > 0) {
            $ventaDetalle = Venta::getVentaDetalle($venta_id);
        }

        $activePage = 'boleta';
        $pageTitle = 'Comprobante de Pago';
        $extraCss = 'boleta.css';
        $viewFile = 'boleta.php';
        
        // Cargar vista en la plantilla maestra
        require_once BASE_DIR . '/views/layout.php';
    }

    /**
     * Permite al administrador editar una boleta/venta.
     */
    public function editar() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            exit;
        }

        $db = \Config\Database::getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $placa = trim($_POST['placa_vehiculo'] ?? '');
            $metodo_pago = trim($_POST['metodo_pago'] ?? '');
            $litros = floatval($_POST['litros'] ?? 0);
            $total = floatval($_POST['total'] ?? 0);

            if ($id <= 0 || $litros <= 0 || $total <= 0 || empty($metodo_pago)) {
                $_SESSION['error'] = 'Por favor, complete todos los campos con valores válidos.';
                header('Location: /reportes');
                exit;
            }

            try {
                $db->beginTransaction();

                // 1. Obtener la venta actual para calcular la diferencia de litros
                $stmtVenta = $db->prepare("SELECT surtidor_id, litros FROM ventas WHERE id = ? FOR UPDATE");
                $stmtVenta->execute([$id]);
                $venta = $stmtVenta->fetch();

                if (!$venta) {
                    throw new \Exception("Venta no encontrada.");
                }

                $surtidor_id = $venta['surtidor_id'];
                $litros_viejos = floatval($venta['litros']);
                $diferencia_litros = $litros - $litros_viejos;

                // 2. Obtener el tanque asociado al surtidor
                $stmtSurtidor = $db->prepare("SELECT inventario_id FROM surtidores WHERE id = ?");
                $stmtSurtidor->execute([$surtidor_id]);
                $surtidor = $stmtSurtidor->fetch();

                if ($surtidor) {
                    $inventario_id = $surtidor['inventario_id'];
                    // Actualizar inventario (tanque subterráneo) restando la diferencia de litros
                    $stmtStock = $db->prepare("UPDATE inventario SET stock_actual = stock_actual - ? WHERE id = ?");
                    $stmtStock->execute([$diferencia_litros, $inventario_id]);

                    // Actualizar lectura acumulada de la manguera
                    $stmtLectura = $db->prepare("UPDATE surtidores SET lectura_acumulada_litros = lectura_acumulada_litros + ? WHERE id = ?");
                    $stmtLectura->execute([$diferencia_litros, $surtidor_id]);
                }

                // 3. Actualizar los datos de la venta
                $stmtUpdate = $db->prepare("UPDATE ventas SET placa_vehiculo = ?, metodo_pago = ?, litros = ?, total = ? WHERE id = ?");
                $stmtUpdate->execute([
                    !empty($placa) ? strtoupper($placa) : null,
                    $metodo_pago,
                    $litros,
                    $total,
                    $id
                ]);

                $db->commit();
                $_SESSION['success'] = "¡Boleta #$id editada y cuadrada con éxito! El inventario fue actualizado.";
            } catch (\Exception $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                $_SESSION['error'] = 'Error al editar la venta: ' . $e->getMessage();
            }

            header('Location: /reportes');
            exit;
        } else {
            // Cargar datos vía GET en formato JSON para el modal interactivo
            $id = intval($_GET['id'] ?? 0);
            $venta = null;
            if ($id > 0) {
                $query = "SELECT v.*, c.nombre as combustible_nombre, c.precio_litro 
                          FROM ventas v 
                          JOIN combustibles c ON v.combustible_id = c.id 
                          WHERE v.id = ? LIMIT 1";
                $stmt = $db->prepare($query);
                $stmt->execute([$id]);
                $venta = $stmt->fetch(\PDO::FETCH_ASSOC);
            }

            header('Content-Type: application/json');
            echo json_encode($venta);
            exit;
        }
    }

    /**
     * Permite al administrador eliminar/anular una boleta/venta.
     */
    public function eliminar() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'Operación no permitida.';
            header('Location: /reportes');
            exit;
        }

        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID de venta no válido.';
            header('Location: /reportes');
            exit;
        }

        $db = \Config\Database::getConnection();

        try {
            $db->beginTransaction();

            // 1. Obtener datos de la venta para restaurar el stock
            $stmtVenta = $db->prepare("SELECT surtidor_id, litros FROM ventas WHERE id = ? FOR UPDATE");
            $stmtVenta->execute([$id]);
            $venta = $stmtVenta->fetch();

            if (!$venta) {
                throw new \Exception("Venta no encontrada.");
            }

            $surtidor_id = $venta['surtidor_id'];
            $litros = floatval($venta['litros']);

            // 2. Obtener el tanque asociado al surtidor
            $stmtSurtidor = $db->prepare("SELECT inventario_id FROM surtidores WHERE id = ?");
            $stmtSurtidor->execute([$surtidor_id]);
            $surtidor = $stmtSurtidor->fetch();

            if ($surtidor) {
                $inventario_id = $surtidor['inventario_id'];
                // Devolver los litros al tanque de almacenamiento subterráneo
                $stmtStock = $db->prepare("UPDATE inventario SET stock_actual = stock_actual + ? WHERE id = ?");
                $stmtStock->execute([$litros, $inventario_id]);

                // Descontar de la lectura acumulada del surtidor
                $stmtLectura = $db->prepare("UPDATE surtidores SET lectura_acumulada_litros = lectura_acumulada_litros - ? WHERE id = ?");
                $stmtLectura->execute([$litros, $surtidor_id]);
            }

            // 3. Eliminar físicamente la transacción
            $stmtDelete = $db->prepare("DELETE FROM ventas WHERE id = ?");
            $stmtDelete->execute([$id]);

            $db->commit();
            $_SESSION['success'] = "¡Boleta #$id anulada con éxito! Se devolvieron $litros Gal al tanque subterráneo.";
        } catch (\Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $_SESSION['error'] = 'Error al anular la boleta: ' . $e->getMessage();
        }

        header('Location: /reportes');
        exit;
    }
}
