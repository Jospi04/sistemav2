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
}
