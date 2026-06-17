<?php
namespace Controller;

use Model\Venta;

class VentasController {
    /**
     * Muestra la interfaz de pedido o procesa el registro de la venta.
     */
    public function registrar() {
        // Asegurar que el usuario está logueado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: login');
            exit;
        }

        // Obtener combos para rellenar el formulario dinámicamente
        $surtidores = Venta::getSurtidoresActivos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $combo_id = intval($_POST['surtidor_id'] ?? 0);
            $cantidad = floatval($_POST['litros'] ?? 0);
            $mesa_cliente = trim($_POST['placa_vehiculo'] ?? '');
            $metodo_pago = trim($_POST['metodo_pago'] ?? '');

            // Validación básica
            if ($combo_id <= 0 || $cantidad <= 0 || empty($metodo_pago)) {
                $_SESSION['error'] = 'Por favor, complete todos los campos obligatorios del pedido.';
                header('Location: ventas');
                exit;
            }

            // Buscar el combo en la lista para obtener el producto y precio unitario reales
            $surtidorSeleccionado = null;
            foreach ($surtidores as $s) {
                if (intval($s['id']) === $combo_id) {
                    $surtidorSeleccionado = $s;
                    break;
                }
            }

            if (!$surtidorSeleccionado) {
                $_SESSION['error'] = 'El combo seleccionado no se encuentra activo o no existe.';
                header('Location: ventas');
                exit;
            }

            $producto_id = $surtidorSeleccionado['producto_id'];
            $precio_venta = floatval($surtidorSeleccionado['precio_litro']);

            // Cálculo del total
            $total = round($cantidad * $precio_venta, 2);

            // Guardar en base de datos llamando al modelo
            $usuario_id = $_SESSION['usuario_id'];
            $venta_id = Venta::guardar($usuario_id, $combo_id, $producto_id, $cantidad, $precio_venta, $total, $mesa_cliente, $metodo_pago);

            if ($venta_id) {
                $_SESSION['success'] = "¡Pedido registrado correctamente! Total: S/. " . number_format($total, 2) . " por " . floatval($cantidad) . " Porción(es) de " . $surtidorSeleccionado['combustible_nombre'];
                $_SESSION['last_venta_id'] = $venta_id;
                header('Location: ventas');
                exit;
            } else {
                $_SESSION['error'] = 'Error al registrar el pedido en la base de datos (Stock insuficiente o error de conexión).';
                header('Location: ventas');
                exit;
            }
        } else {
            // Cargar la vista encapsulada en el layout maestro
            $activePage = 'ventas';
            $pageTitle = 'Tomar Pedido';
            $extraCss = 'ventas.css';
            $extraJs = 'ventas.js';
            $viewFile = 'ventas.php';
            require_once BASE_DIR . '/views/layout.php';
        }
    }

    /**
     * Muestra el comprobante de boleta del último pedido.
     */
    public function verBoleta() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: login');
            exit;
        }

        // Buscar ID de venta
        $venta_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_SESSION['last_venta_id']) ? intval($_SESSION['last_venta_id']) : 0);

        $ventaDetalle = null;
        if ($venta_id > 0) {
            $ventaDetalle = Venta::getVentaDetalle($venta_id);
        }

        $activePage = 'boleta';
        $pageTitle = 'Comprobante de Pago';
        $extraCss = 'boleta.css';
        $extraJs = 'boleta.js';
        $viewFile = 'boleta.php';
        
        // Cargar vista en la plantilla maestra
        require_once BASE_DIR . '/views/layout.php';
    }

    /**
     * Permite al administrador editar un pedido/boleta.
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
            $mesa_cliente = trim($_POST['placa_vehiculo'] ?? '');
            $metodo_pago = trim($_POST['metodo_pago'] ?? '');
            $cantidad = floatval($_POST['litros'] ?? 0);
            $total = floatval($_POST['total'] ?? 0);

            if ($id <= 0 || $cantidad <= 0 || $total <= 0 || empty($metodo_pago)) {
                $_SESSION['error'] = 'Por favor, complete todos los campos con valores válidos.';
                header('Location: reportes');
                exit;
            }

            try {
                $db->beginTransaction();

                // 1. Obtener la venta actual para calcular la diferencia de porciones
                $stmtVenta = $db->prepare("SELECT combo_id, cantidad FROM ventas WHERE id = ? FOR UPDATE");
                $stmtVenta->execute([$id]);
                $venta = $stmtVenta->fetch();

                if (!$venta) {
                    throw new \Exception("Pedido no encontrado.");
                }

                $combo_id = $venta['combo_id'];
                $cantidad_vieja = floatval($venta['cantidad']);
                $diferencia_cantidad = $cantidad - $cantidad_vieja;

                // 2. Obtener el insumo asociado al combo
                $stmtCombo = $db->prepare("SELECT inventario_id FROM combos WHERE id = ?");
                $stmtCombo->execute([$combo_id]);
                $combo = $stmtCombo->fetch();

                if ($combo) {
                    $inventario_id = $combo['inventario_id'];
                    // Actualizar inventario restando la diferencia de porciones
                    $stmtStock = $db->prepare("UPDATE inventario SET stock_actual = stock_actual - ? WHERE id = ?");
                    $stmtStock->execute([$diferencia_cantidad, $inventario_id]);

                    // Actualizar ventas acumuladas del combo
                    $stmtLectura = $db->prepare("UPDATE combos SET ventas_acumuladas = ventas_acumuladas + ? WHERE id = ?");
                    $stmtLectura->execute([$diferencia_cantidad, $combo_id]);
                }

                // 3. Actualizar los datos de la venta
                $stmtUpdate = $db->prepare("UPDATE ventas SET mesa_cliente = ?, metodo_pago = ?, cantidad = ?, total = ? WHERE id = ?");
                $stmtUpdate->execute([
                    !empty($mesa_cliente) ? $mesa_cliente : null,
                    $metodo_pago,
                    $cantidad,
                    $total,
                    $id
                ]);

                $db->commit();
                $_SESSION['success'] = "¡Boleta #$id editada con éxito! El stock de insumos fue actualizado.";
            } catch (\Exception $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                $_SESSION['error'] = 'Error al editar el pedido: ' . $e->getMessage();
            }

            header('Location: reportes');
            exit;
        } else {
            // Cargar datos vía GET en formato JSON para el modal interactivo
            $id = intval($_GET['id'] ?? 0);
            $venta = null;
            if ($id > 0) {
                $query = "SELECT v.*, c.nombre as combustible_nombre, c.precio_venta as precio_litro,
                                 v.mesa_cliente as placa_vehiculo, v.cantidad as litros
                          FROM ventas v 
                          JOIN productos c ON v.producto_id = c.id 
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
     * Permite al administrador anular un pedido/boleta.
     */
    public function eliminar() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'Operación no permitida.';
            header('Location: reportes');
            exit;
        }

        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID de pedido no válido.';
            header('Location: reportes');
            exit;
        }

        $db = \Config\Database::getConnection();

        try {
            $db->beginTransaction();

            // 1. Obtener datos de la venta para restaurar el stock
            $stmtVenta = $db->prepare("SELECT combo_id, cantidad FROM ventas WHERE id = ? FOR UPDATE");
            $stmtVenta->execute([$id]);
            $venta = $stmtVenta->fetch();

            if (!$venta) {
                throw new \Exception("Pedido no encontrado.");
            }

            $combo_id = $venta['combo_id'];
            $cantidad = floatval($venta['cantidad']);

            // 2. Obtener el insumo asociado al combo
            $stmtCombo = $db->prepare("SELECT inventario_id FROM combos WHERE id = ?");
            $stmtCombo->execute([$combo_id]);
            $combo = $stmtCombo->fetch();

            if ($combo) {
                $inventario_id = $combo['inventario_id'];
                // Devolver las porciones al almacén
                $stmtStock = $db->prepare("UPDATE inventario SET stock_actual = stock_actual + ? WHERE id = ?");
                $stmtStock->execute([$cantidad, $inventario_id]);

                // Descontar de las ventas acumuladas del combo
                $stmtLectura = $db->prepare("UPDATE combos SET ventas_acumuladas = ventas_acumuladas - ? WHERE id = ?");
                $stmtLectura->execute([$cantidad, $combo_id]);
            }

            // 3. Eliminar físicamente la transacción
            $stmtDelete = $db->prepare("DELETE FROM ventas WHERE id = ?");
            $stmtDelete->execute([$id]);

            $db->commit();
            $_SESSION['success'] = "¡Pedido #$id anula con éxito! Se devolvieron $cantidad porciones al stock.";
        } catch (\Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $_SESSION['error'] = 'Error al anular el pedido: ' . $e->getMessage();
        }

        header('Location: reportes');
        exit;
    }
}
