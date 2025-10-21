-- Base de datos: Contratos en cuotas
-- Generado para importación en phpMyAdmin

-- Eliminar la base de datos si existe y crearla nuevamente
DROP DATABASE IF EXISTS `Contratos_en_cuotas`;
CREATE DATABASE `Contratos_en_cuotas` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Contratos_en_cuotas`;

-- ============================================
-- Tabla auxiliar: Cuotas
-- ============================================
CREATE TABLE `Cuotas` (
  `Cod` VARCHAR(2) NOT NULL PRIMARY KEY,
  `Descrip` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar datos en tabla Cuotas
INSERT INTO `Cuotas` (`Cod`, `Descrip`) VALUES
('01', 'Una'),
('02', 'Dos'),
('03', 'Tres'),
('04', 'Cuatro'),
('05', 'Cinco'),
('06', 'Seis');

-- ============================================
-- Tabla principal: ContratoDeCuotas
-- ============================================
CREATE TABLE `ContratoDeCuotas` (
  `ID_Contratos` VARCHAR(10) NOT NULL PRIMARY KEY,
  `DNI_Deudor` VARCHAR(20) NOT NULL,
  `Apellido_Nombres` VARCHAR(100) NOT NULL,
  `Monto_total_financiado` DECIMAL(12,2) NOT NULL,
  `FechaContrato` DATE NOT NULL,
  `NroDeCuotas` VARCHAR(2) NOT NULL,
  `QR` VARCHAR(255) DEFAULT '',
  FOREIGN KEY (`NroDeCuotas`) REFERENCES `Cuotas`(`Cod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar datos en tabla ContratoDeCuotas
INSERT INTO `ContratoDeCuotas` (`ID_Contratos`, `DNI_Deudor`, `Apellido_Nombres`, `Monto_total_financiado`, `FechaContrato`, `NroDeCuotas`, `QR`) VALUES
('A01', '23456789', 'Alvarez, Rodolfo', 75000.00, '2025-01-10', '03', ''),
('A02', '43235897', 'Borzone, Federico', 234000.00, '2025-01-14', '01', ''),
('A03', '27538965', 'Carballo, Santiago', 132400.00, '2025-02-09', '02', ''),
('B29', '16523201', 'Duncan, Juan', 540000.00, '2025-02-22', '06', ''),
('C14', '93576241', 'Enriques, Ana', 125000.00, '2025-03-04', '02', ''),
('C47', '21357965', 'Fernandez, Sergio', 425000.00, '2025-03-21', '06', ''),
('B51', '32567277', 'Gonzalez, Pablo', 87500.00, '2025-03-28', '03', ''),
('D23', '11543652', 'Hernandez, Lucia', 832000.00, '2025-04-04', '06', ''),
('D35', '26965344', 'Irigoitia, Jose', 76000.00, '2025-04-14', '01', ''),
('A10', '40275318', 'Juarez, Diego', 245000.00, '2025-04-21', '02', ''),
('T28', '32919303', 'Krupp, Margaret', 723200.00, '2025-05-02', '06', ''),
('B72', '26398299', 'Lazarte, Mario', 101900.00, '2025-05-11', '03', ''),
('T31', '39612653', 'Mansell, Nadia', 96000.00, '2025-05-18', '01', ''),
('A21', '12321848', 'Nadal, Raul', 527500.00, '2025-05-23', '04', ''),
('D38', '32458625', 'Ochoa, Laura', 85000.00, '2025-06-04', '03', '');

-- ============================================
-- Consultas de verificación (opcional)
-- ============================================
-- SELECT * FROM Cuotas;
-- SELECT * FROM ContratoDeCuotas;
-- SELECT COUNT(*) AS TotalContratos FROM ContratoDeCuotas;
-- SELECT SUM(Monto_total_financiado) AS SumaTotal FROM ContratoDeCuotas;