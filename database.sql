-- ============================================================
-- SCRIPT DE INICIALIZACIÓN Y CARGA DE BASE DE DATOS
-- PROYECTO: SISTEMA DE CONTROL DE BROSTERIA 24/7
-- ============================================================

-- Crear base de datos con codificación moderna de soporte emoji y caracteres especiales
CREATE DATABASE IF NOT EXISTS `sistema_grifo` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sistema_grifo`;

-- Desactivar restricciones de integridad para recrear las tablas de forma limpia
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `reabastecimientos`;
DROP TABLE IF EXISTS `ventas`;
DROP TABLE IF EXISTS `combos`;
DROP TABLE IF EXISTS `inventario`;
DROP TABLE IF EXISTS `productos`;
DROP TABLE IF EXISTS `usuarios`;

-- ============================================================
-- 1. TABLA: usuarios (Administradores y Cajeros/Mozos)
-- ============================================================
CREATE TABLE `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `usuario` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `rol` VARCHAR(20) NOT NULL DEFAULT 'operario' COMMENT 'admin, operario',
    `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. TABLA: productos (Catálogo Oficial de Productos Base)
-- ============================================================
CREATE TABLE `productos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(50) NOT NULL UNIQUE,
    `precio_venta` DECIMAL(10,2) NOT NULL,
    `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. TABLA: inventario (Stock de Insumos/Ingredientes en Almacén)
-- ============================================================
CREATE TABLE `inventario` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `producto_id` INT NOT NULL,
    `capacidad_maxima` DECIMAL(12,2) NOT NULL COMMENT 'Capacidad máxima de almacenamiento en porciones/unidades',
    `stock_actual` DECIMAL(12,2) NOT NULL COMMENT 'Unidades o porciones disponibles en tiempo real',
    `ultima_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_inventario_productos` FOREIGN KEY (`producto_id`) 
      REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. TABLA: combos (Opciones del Menú en Venta)
-- ============================================================
CREATE TABLE `combos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(50) NOT NULL COMMENT 'Ej. Combo Mostrito Familiar',
    `producto_id` INT NOT NULL,
    `inventario_id` INT NOT NULL COMMENT 'Insumo del almacén asociado',
    `ventas_acumuladas` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Contador de porciones vendidas',
    `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_combos_productos` FOREIGN KEY (`producto_id`) 
      REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_combos_inventario` FOREIGN KEY (`inventario_id`) 
      REFERENCES `inventario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. TABLA: ventas (Registro Histórico de Pedidos)
-- ============================================================
CREATE TABLE `ventas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT NOT NULL COMMENT 'Cajero responsable de la venta',
    `combo_id` INT NOT NULL COMMENT 'Combo vendido',
    `producto_id` INT NOT NULL COMMENT 'Producto base asociado',
    `cantidad` DECIMAL(10,2) NOT NULL COMMENT 'Cantidad de porciones despachadas',
    `precio_unitario` DECIMAL(10,2) NOT NULL COMMENT 'Precio unitario del combo',
    `total` DECIMAL(10,2) NOT NULL COMMENT 'Importe total cobrado',
    `mesa_cliente` VARCHAR(50) DEFAULT NULL COMMENT 'Mesa o nombre del cliente',
    `metodo_pago` VARCHAR(30) NOT NULL DEFAULT 'Efectivo' COMMENT 'Efectivo, Tarjeta, Yape/Plin',
    `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_ventas_usuarios` FOREIGN KEY (`usuario_id`) 
      REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_ventas_combos` FOREIGN KEY (`combo_id`) 
      REFERENCES `combos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_ventas_productos` FOREIGN KEY (`producto_id`) 
      REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. TABLA: reabastecimientos (Historial de Restock de Insumos)
-- ============================================================
CREATE TABLE `reabastecimientos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `inventario_id` INT NOT NULL,
    `cantidad` DECIMAL(12,2) NOT NULL,
    `usuario_id` INT NOT NULL,
    `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_reabastecimientos_inventario` FOREIGN KEY (`inventario_id`) 
      REFERENCES `inventario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_reabastecimientos_usuarios` FOREIGN KEY (`usuario_id`) 
      REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reactivar la validación de llaves foráneas para mantener la consistencia
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- CARGA DE DATOS SEMILLAS (SEEDERS) PARA INICIALIZAR LA BROSTERÍA
-- ============================================================

-- A. Cuentas de Acceso por Defecto
-- admin: admin123 (Cifrado BCRYPT)
-- operador: operador123 (Cifrado BCRYPT)
INSERT INTO `usuarios` (`nombre`, `usuario`, `password`, `rol`) VALUES
('Piero Administrador', 'admin', '$2y$10$w0M8jVfE4.rT4aQ9gN3YVud7E5qM/u6T6yYQ/BvXk8hTqLp5fN/8a', 'admin'),
('Carlos Cajero', 'operador', '$2y$10$0zX7f7q8MvUeX5bZ7Wf2be2r4YyK8M07Xw7fNqZ4r2M6YV3Z6n5d.', 'operario');

-- B. Catálogo de Productos Base (Precios oficiales de porciones)
INSERT INTO `productos` (`nombre`, `precio_venta`) VALUES
('Mostrito Broster', 18.50),
('Broster Clásico', 14.50),
('Salchipapa Especial', 13.50),
('Alitas BBQ', 16.00),
('Gaseosa 500ml', 4.50);

-- C. Almacén de Insumos (Inventario de Porciones Iniciales)
INSERT INTO `inventario` (`producto_id`, `capacidad_maxima`, `stock_actual`) VALUES
(1, 1000.00, 850.00), -- Insumo Mostrito (Capacidad: 1000 porciones, Stock: 850)
(2, 1200.00, 980.00), -- Insumo Broster (Capacidad: 1200 porciones, Stock: 980)
(3, 1000.00, 750.00), -- Insumo Salchipapa (Capacidad: 1000 porciones, Stock: 750)
(4, 800.00, 640.00),  -- Insumo Alitas (Capacidad: 800 porciones, Stock: 640)
(5, 2000.00, 1500.00); -- Insumo Gaseosas (Capacidad: 2000 unidades, Stock: 1500)

-- D. Combos en el Menú Asignados a los Insumos
INSERT INTO `combos` (`nombre`, `producto_id`, `inventario_id`, `ventas_acumuladas`) VALUES
('Combo Mostrito Broster + Gaseosa', 1, 1, 120.00),
('Broster Clásico Personal', 2, 2, 340.00),
('Salchipapa Especial Familiar', 3, 3, 90.00),
('Combo Alitas BBQ x6 + Papas', 4, 4, 150.00),
('Gaseosa Personal Helada', 5, 5, 410.00);

-- E. Ventas Semilla Recientes para Rellenar Dashboard
INSERT INTO `ventas` (`usuario_id`, `combo_id`, `producto_id`, `cantidad`, `precio_unitario`, `total`, `mesa_cliente`, `metodo_pago`, `fecha`) VALUES
(2, 1, 1, 2.00, 18.50, 37.00, 'Mesa 4', 'Efectivo', NOW() - INTERVAL 4 HOUR),
(2, 2, 2, 1.00, 14.50, 14.50, 'Mesa 12', 'Tarjeta', NOW() - INTERVAL 3 HOUR),
(2, 3, 3, 3.00, 13.50, 40.50, 'Para Llevar', 'Yape/Plin', NOW() - INTERVAL 2 HOUR),
(2, 4, 4, 1.00, 16.00, 16.00, 'Mesa 8', 'Yape/Plin', NOW() - INTERVAL 1 HOUR);
