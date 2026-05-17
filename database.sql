-- ==========================================
-- SCRIPT INICIAL DE BASE DE DATOS - GRIFO
-- ==========================================

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS `sistema_grifo` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sistema_grifo`;

-- 1. TABLA: usuarios (Administradores y Operadores de Surtidor)
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `usuario` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `rol` VARCHAR(20) NOT NULL COMMENT 'admin, operario',
    `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. TABLA: combustibles (Catálogo de Productos)
CREATE TABLE IF NOT EXISTS `combustibles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(50) NOT NULL UNIQUE,
    `precio_litro` DECIMAL(10,2) NOT NULL,
    `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. TABLA: inventario (Tanques físicos de almacenamiento)
CREATE TABLE IF NOT EXISTS `inventario` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `combustible_id` INT NOT NULL,
    `capacidad_maxima` DECIMAL(12,2) NOT NULL COMMENT 'Capacidad en litros',
    `stock_actual` DECIMAL(12,2) NOT NULL COMMENT 'Stock físico en litros',
    `ultima_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`combustible_id`) REFERENCES `combustibles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. TABLA: surtidores (Mangueras asociadas a tanques de almacenamiento)
CREATE TABLE IF NOT EXISTS `surtidores` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(50) NOT NULL COMMENT 'Ej. Surtidor 1 - Manguera G95',
    `combustible_id` INT NOT NULL,
    `inventario_id` INT NOT NULL COMMENT 'Tanque físico asociado',
    `lectura_acumulada_litros` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Contador digital/mecánico',
    `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`combustible_id`) REFERENCES `combustibles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`inventario_id`) REFERENCES `inventario`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. TABLA: ventas (Registro de Transacciones Comerciales)
CREATE TABLE IF NOT EXISTS `ventas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT NOT NULL COMMENT 'Grisfero que realiza el despacho',
    `surtidor_id` INT NOT NULL COMMENT 'Manguera utilizada',
    `combustible_id` INT NOT NULL COMMENT 'Combustible despachado',
    `litros` DECIMAL(10,2) NOT NULL COMMENT 'Cantidad de litros vendidos',
    `precio_unitario` DECIMAL(10,2) NOT NULL,
    `total` DECIMAL(10,2) NOT NULL COMMENT 'Litros * Precio unitario',
    `placa_vehiculo` VARCHAR(15) DEFAULT NULL,
    `metodo_pago` VARCHAR(30) NOT NULL COMMENT 'Efectivo, Tarjeta, Yape/Plin',
    `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`surtidor_id`) REFERENCES `surtidores`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`combustible_id`) REFERENCES `combustibles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ==========================================
-- INSERCIÓN DE DATOS DE SEMILLA (SEEDS)
-- ==========================================

-- Usuarios por defecto
-- admin: admin123 (hash bcrypt)
-- operador: operador123 (hash bcrypt)
INSERT INTO `usuarios` (`nombre`, `usuario`, `password`, `rol`) VALUES
('Administrador del Grifo', 'admin', '$2y$10$w0M8jVfE4.rT4aQ9gN3YVud7E5qM/u6T6yYQ/BvXk8hTqLp5fN/8a', 'admin'),
('Carlos Grisfero', 'operador', '$2y$10$0zX7f7q8MvUeX5bZ7Wf2be2r4YyK8M07Xw7fNqZ4r2M6YV3Z6n5d.', 'operario');

-- Combustibles del catálogo (Precios oficiales por Galón en Perú)
INSERT INTO `combustibles` (`nombre`, `precio_litro`) VALUES
('Gasolina 90 Octanos', 19.30),
('Gasolina 95 Octanos', 22.14),
('Gasolina 97 Octanos', 23.50),
('Diesel B5 S-50', 19.68),
('GLP Vehicular', 9.50);

-- Tanques físicos (Inventario inicial en litros)
INSERT INTO `inventario` (`combustible_id`, `capacidad_maxima`, `stock_actual`) VALUES
(1, 15000.00, 12000.00), -- Tanque Gasolina 90 (Capacidad: 15,000 L, Stock: 12,000 L)
(2, 15000.00, 10500.00), -- Tanque Gasolina 95 (Capacidad: 15,000 L, Stock: 10,500 L)
(3, 10000.00, 7800.00),  -- Tanque Gasolina 97 (Capacidad: 10,000 L, Stock: 7,800 L)
(4, 20000.00, 16400.00), -- Tanque Diesel (Capacidad: 20,000 L, Stock: 16,400 L)
(5, 8000.00, 5200.00);    -- Tanque GLP (Capacidad: 8,000 L, Stock: 5,200 L)

-- Surtidores y mangueras asignadas
INSERT INTO `surtidores` (`nombre`, `combustible_id`, `inventario_id`, `lectura_acumulada_litros`) VALUES
('Lado A - Surtidor 1 (G90)', 1, 1, 142500.50),
('Lado A - Surtidor 2 (G95)', 2, 2, 218900.20),
('Lado B - Surtidor 3 (G97)', 3, 3, 98450.80),
('Lado B - Surtidor 4 (Diesel)', 4, 4, 384700.10),
('Isla 3 - Surtidor 5 (GLP)', 5, 5, 87400.40);
