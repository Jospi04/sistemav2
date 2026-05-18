-- ============================================================
-- SCRIPT DE INICIALIZACIÓN Y CARGA DE BASE DE DATOS
-- PROYECTO: SISTEMA DE CONTROL DE GRIFO JOSPERÚ 2026
-- CLIENTE/PROFESOR: EVALUACIÓN DE INGENIERÍA DE SOFTWARE
-- ============================================================

-- Crear base de datos con codificación moderna de soporte emoji y caracteres especiales
CREATE DATABASE IF NOT EXISTS `sistema_grifo` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sistema_grifo`;

-- Desactivar restricciones de integridad para recrear las tablas de forma limpia
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `ventas`;
DROP TABLE IF EXISTS `surtidores`;
DROP TABLE IF EXISTS `inventario`;
DROP TABLE IF EXISTS `combustibles`;
DROP TABLE IF EXISTS `usuarios`;

-- ============================================================
-- 1. TABLA: usuarios (Administradores y Operarios de Isla)
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
-- 2. TABLA: combustibles (Catálogo Oficial de Precios en Grifo)
-- ============================================================
CREATE TABLE `combustibles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(50) NOT NULL UNIQUE,
    `precio_litro` DECIMAL(10,2) NOT NULL,
    `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. TABLA: inventario (Tanques Subterráneos de Almacenamiento)
-- ============================================================
CREATE TABLE `inventario` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `combustible_id` INT NOT NULL,
    `capacidad_maxima` DECIMAL(12,2) NOT NULL COMMENT 'Capacidad física del tanque en galones',
    `stock_actual` DECIMAL(12,2) NOT NULL COMMENT 'Galones físicos disponibles en tiempo real',
    `ultima_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_inventario_combustibles` FOREIGN KEY (`combustible_id`) 
      REFERENCES `combustibles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. TABLA: surtidores (Dispensadores Físicos en Isla)
-- ============================================================
CREATE TABLE `surtidores` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(50) NOT NULL COMMENT 'Ej. Surtidor 1 - Isla A',
    `combustible_id` INT NOT NULL,
    `inventario_id` INT NOT NULL COMMENT 'Tanque físico asociado',
    `lectura_acumulada_litros` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Contador mecánico de galonaje',
    `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_surtidores_combustibles` FOREIGN KEY (`combustible_id`) 
      REFERENCES `combustibles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_surtidores_inventario` FOREIGN KEY (`inventario_id`) 
      REFERENCES `inventario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. TABLA: ventas (Registro Histórico de Despachos)
-- ============================================================
CREATE TABLE `ventas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT NOT NULL COMMENT 'Vendedor grifero de la transacción',
    `surtidor_id` INT NOT NULL COMMENT 'Surtidor emisor',
    `combustible_id` INT NOT NULL COMMENT 'Combustible despachado',
    `litros` DECIMAL(10,2) NOT NULL COMMENT 'Cantidad despachada (Galones/Litros)',
    `precio_unitario` DECIMAL(10,2) NOT NULL COMMENT 'Precio pactado en la transacción',
    `total` DECIMAL(10,2) NOT NULL COMMENT 'Importe total cobrado (Litros * Precio)',
    `placa_vehiculo` VARCHAR(15) DEFAULT NULL,
    `metodo_pago` VARCHAR(30) NOT NULL DEFAULT 'Efectivo' COMMENT 'Efectivo, Tarjeta, Yape, Plin',
    `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_ventas_usuarios` FOREIGN KEY (`usuario_id`) 
      REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_ventas_surtidores` FOREIGN KEY (`surtidor_id`) 
      REFERENCES `surtidores` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_ventas_combustibles` FOREIGN KEY (`combustible_id`) 
      REFERENCES `combustibles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reactivar la validación de llaves foráneas para mantener la consistencia
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- CARGA DE DATOS SEMILLAS (SEEDERS) PARA INICIALIZAR EL GRIFO
-- ============================================================

-- A. Cuentas de Acceso por Defecto
-- admin: admin123 (Cifrado BCRYPT robusto)
-- operador: operador123 (Cifrado BCRYPT robusto)
INSERT INTO `usuarios` (`nombre`, `usuario`, `password`, `rol`) VALUES
('Piero Administrador', 'admin', '$2y$10$w0M8jVfE4.rT4aQ9gN3YVud7E5qM/u6T6yYQ/BvXk8hTqLp5fN/8a', 'admin'),
('Carlos Grifero Pista', 'operador', '$2y$10$0zX7f7q8MvUeX5bZ7Wf2be2r4YyK8M07Xw7fNqZ4r2M6YV3Z6n5d.', 'operario');

-- B. Catálogo de Combustibles (Precios oficiales en Perú por Galón)
INSERT INTO `combustibles` (`nombre`, `precio_litro`) VALUES
('Gasolina 90 Octanos', 19.30),
('Gasolina 95 Octanos', 22.14),
('Gasolina 97 Octanos', 23.50),
('Diesel B5 S-50', 19.68),
('GLP Vehicular', 9.50);

-- C. Tanques Subterráneos (Inventario Físico Inicial)
INSERT INTO `inventario` (`combustible_id`, `capacidad_maxima`, `stock_actual`) VALUES
(1, 15000.00, 12000.00), -- Tanque Gasolina 90 (Capacidad: 15K, Stock: 12K Galones)
(2, 15000.00, 10500.00), -- Tanque Gasolina 95 (Capacidad: 15K, Stock: 10.5K Galones)
(3, 10000.00, 7800.00),  -- Tanque Gasolina 97 (Capacidad: 10K, Stock: 7.8K Galones)
(4, 20000.00, 16400.00), -- Tanque Diesel (Capacidad: 20K, Stock: 16.4K Galones)
(5, 8000.00, 5200.00);    -- Tanque GLP (Capacidad: 8K, Stock: 5.2K Galones)

-- D. Surtidores en Pista Asignados a Tanques
INSERT INTO `surtidores` (`nombre`, `combustible_id`, `inventario_id`, `lectura_acumulada_litros`) VALUES
('Lado A - Surtidor 1 (G90)', 1, 1, 142500.50),
('Lado A - Surtidor 2 (G95)', 2, 2, 218900.20),
('Lado B - Surtidor 3 (G97)', 3, 3, 98450.80),
('Lado B - Surtidor 4 (Diesel)', 4, 4, 384700.10),
('Isla 3 - Surtidor 5 (GLP)', 5, 5, 87400.40);

-- E. Ventas de Semilla Recientes (Para que tu profesor vea el Dashboard con hermosos gráficos al importar)
INSERT INTO `ventas` (`usuario_id`, `surtidor_id`, `combustible_id`, `litros`, `precio_unitario`, `total`, `placa_vehiculo`, `metodo_pago`, `fecha`) VALUES
(2, 1, 1, 10.00, 19.30, 193.00, 'A1F-302', 'Efectivo', NOW() - INTERVAL 4 HOUR),
(2, 2, 2, 8.50, 22.14, 188.19, 'B4X-982', 'Tarjeta', NOW() - INTERVAL 3 HOUR),
(2, 4, 4, 25.00, 19.68, 492.00, 'F8P-192', 'Yape', NOW() - INTERVAL 2 HOUR),
(2, 3, 3, 12.00, 23.50, 282.00, 'C3D-882', 'Plin', NOW() - INTERVAL 1 HOUR);
