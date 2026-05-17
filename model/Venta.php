<?php
namespace Model;

use Config\Database;
use PDO;

class Venta {
    /**
     * Obtiene la lista de mangueras/surtidores activos con sus combustibles y precios asociados.
     */
    public static function getSurtidoresActivos() {
        $db = Database::getConnection();
        
        $query = "SELECT s.*, c.nombre as combustible_nombre, c.precio_litro 
                  FROM surtidores s 
                  JOIN combustibles c ON s.combustible_id = c.id
                  ORDER BY s.id ASC";
                  
        $stmt = $db->query($query);
        return $stmt->fetchAll();
    }

    /**
     * Registra una venta en la base de datos y descuenta automáticamente del inventario.
     * Utiliza una transacción MySQL para asegurar integridad de stock y lecturas.
     */
    public static function guardar($usuario_id, $surtidor_id, $combustible_id, $litros, $precio_unitario, $total, $placa_vehiculo, $metodo_pago) {
        $db = Database::getConnection();

        try {
            // 1. Iniciar Transacción SQL
            $db->beginTransaction();

            // 2. Obtener el inventario_id (tanque físico) asociado a este surtidor y bloquear la fila para evitar colisiones
            $stmtSurtidor = $db->prepare("SELECT inventario_id FROM surtidores WHERE id = :surtidor_id FOR UPDATE");
            $stmtSurtidor->execute([':surtidor_id' => $surtidor_id]);
            $surtidor = $stmtSurtidor->fetch();

            if (!$surtidor) {
                throw new \Exception("Surtidor no encontrado.");
            }

            $inventario_id = $surtidor['inventario_id'];

            // 3. Descontar litros del inventario (tanque de almacenamiento)
            $stmtStock = $db->prepare("UPDATE inventario SET stock_actual = stock_actual - :litros WHERE id = :inventario_id");
            $stmtStock->execute([
                ':litros' => $litros,
                ':inventario_id' => $inventario_id
            ]);

            // 4. Actualizar la lectura mecánica acumulada de la manguera
            $stmtLectura = $db->prepare("UPDATE surtidores SET lectura_acumulada_litros = lectura_acumulada_litros + :litros WHERE id = :surtidor_id");
            $stmtLectura->execute([
                ':litros' => $litros,
                ':surtidor_id' => $surtidor_id
            ]);

            // 5. Registrar la transacción de venta
            $queryVenta = "INSERT INTO ventas (usuario_id, surtidor_id, combustible_id, litros, precio_unitario, total, placa_vehiculo, metodo_pago, fecha) 
                           VALUES (:usuario_id, :surtidor_id, :combustible_id, :litros, :precio_unitario, :total, :placa_vehiculo, :metodo_pago, CURRENT_TIMESTAMP)";
            
            $stmtVenta = $db->prepare($queryVenta);
            $stmtVenta->execute([
                ':usuario_id' => $usuario_id,
                ':surtidor_id' => $surtidor_id,
                ':combustible_id' => $combustible_id,
                ':litros' => $litros,
                ':precio_unitario' => $precio_unitario,
                ':total' => $total,
                ':placa_vehiculo' => !empty($placa_vehiculo) ? strtoupper(trim($placa_vehiculo)) : null,
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
            error_log("Error en transacción de venta (Fase 6): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el detalle completo de una venta para generar el comprobante.
     */
    public static function getVentaDetalle($venta_id) {
        $db = Database::getConnection();
        
        $query = "SELECT v.*, c.nombre as combustible_nombre, s.nombre as surtidor_nombre, u.nombre as usuario_nombre
                  FROM ventas v
                  JOIN combustibles c ON v.combustible_id = c.id
                  JOIN surtidores s ON v.surtidor_id = s.id
                  JOIN usuarios u ON v.usuario_id = u.id
                  WHERE v.id = :venta_id LIMIT 1";
                  
        $stmt = $db->prepare($query);
        $stmt->execute([':venta_id' => $venta_id]);
        return $stmt->fetch();
    }
}
