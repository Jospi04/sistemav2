<?php
namespace Model;

use Config\Database;
use PDO;

class Venta {
    /**
     * Obtiene la lista de combos activos con sus productos y precios asociados.
     */
    public static function getSurtidoresActivos() {
        $db = Database::getConnection();
        
        $query = "SELECT s.*, c.nombre as combustible_nombre, c.precio_venta as precio_litro 
                  FROM combos s 
                  JOIN productos c ON s.producto_id = c.id
                  ORDER BY s.id ASC";
                  
        $stmt = $db->query($query);
        return $stmt->fetchAll();
    }

    /**
     * Registra una venta en la base de datos y descuenta automáticamente del inventario.
     * Utiliza una transacción MySQL para asegurar integridad de stock y lecturas.
     */
    public static function guardar($usuario_id, $combo_id, $producto_id, $cantidad, $precio_unitario, $total, $mesa_cliente, $metodo_pago) {
        $db = Database::getConnection();

        try {
            // 1. Iniciar Transacción SQL
            $db->beginTransaction();

            // 2. Obtener el inventario_id (insumo físico) asociado a este combo y bloquear la fila para evitar colisiones
            $stmtCombo = $db->prepare("SELECT inventario_id FROM combos WHERE id = :combo_id FOR UPDATE");
            $stmtCombo->execute([':combo_id' => $combo_id]);
            $combo = $stmtCombo->fetch();

            if (!$combo) {
                throw new \Exception("Combo no encontrado.");
            }

            $inventario_id = $combo['inventario_id'];

            // 3. Descontar cantidad del inventario (almacén de insumos)
            $stmtStock = $db->prepare("UPDATE inventario SET stock_actual = stock_actual - :cantidad WHERE id = :inventario_id");
            $stmtStock->execute([
                ':cantidad' => $cantidad,
                ':inventario_id' => $inventario_id
            ]);

            // 4. Actualizar las ventas acumuladas de la opción de menú/combo
            $stmtLectura = $db->prepare("UPDATE combos SET ventas_acumuladas = ventas_acumuladas + :cantidad WHERE id = :combo_id");
            $stmtLectura->execute([
                ':cantidad' => $cantidad,
                ':combo_id' => $combo_id
            ]);

            // 5. Registrar la transacción de venta
            $queryVenta = "INSERT INTO ventas (usuario_id, combo_id, producto_id, cantidad, precio_unitario, total, mesa_cliente, metodo_pago, fecha) 
                           VALUES (:usuario_id, :combo_id, :producto_id, :cantidad, :precio_unitario, :total, :mesa_cliente, :metodo_pago, CURRENT_TIMESTAMP)";
            
            $stmtVenta = $db->prepare($queryVenta);
            $stmtVenta->execute([
                ':usuario_id' => $usuario_id,
                ':combo_id' => $combo_id,
                ':producto_id' => $producto_id,
                ':cantidad' => $cantidad,
                ':precio_unitario' => $precio_unitario,
                ':total' => $total,
                ':mesa_cliente' => !empty($mesa_cliente) ? trim($mesa_cliente) : null,
                ':metodo_pago' => $metodo_pago
            ]);

            $lastId = $db->lastInsertId();

            // 6. Confirmar la transacción
            $db->commit();
            return $lastId;

        } catch (\Exception $e) {
            // En caso de error, revertir todos los cambios realizados
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log("Error en transacción de venta (Brosteria): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el detalle completo de una venta para generar el comprobante.
     */
    public static function getVentaDetalle($venta_id) {
        $db = Database::getConnection();
        
        $query = "SELECT v.*, c.nombre as combustible_nombre, s.nombre as surtidor_nombre, u.nombre as usuario_nombre,
                         v.mesa_cliente as placa_vehiculo, v.cantidad as litros
                  FROM ventas v
                  JOIN productos c ON v.producto_id = c.id
                  JOIN combos s ON v.combo_id = s.id
                  JOIN usuarios u ON v.usuario_id = u.id
                  WHERE v.id = :venta_id LIMIT 1";
                  
        $stmt = $db->prepare($query);
        $stmt->execute([':venta_id' => $venta_id]);
        return $stmt->fetch();
    }
}
