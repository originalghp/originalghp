SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `secap` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `secap`;


DROP TABLE IF EXISTS `destinations`;
DROP TABLE IF EXISTS `zones`;
DROP TABLE IF EXISTS `origin`;
DROP TABLE IF EXISTS `metadata`;


CREATE TABLE `origin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `address` VARCHAR(512),
  `latitude` DECIMAL(9,6),
  `longitude` DECIMAL(9,6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `zones` (
  `zone_name` VARCHAR(100) PRIMARY KEY,
  `range_description` VARCHAR(50),
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `destinations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `region` VARCHAR(50),
  `name` VARCHAR(255) NOT NULL,
  `distance_km` INT,
  `zone_name` VARCHAR(100),
  CONSTRAINT `fk_zone_name` FOREIGN KEY (`zone_name`) REFERENCES `zones`(`zone_name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `metadata` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `project` VARCHAR(255),
  `university` VARCHAR(255),
  `team` VARCHAR(100),
  `program` VARCHAR(255),
  `last_updated` DATE,
  `total_destinations` INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `origin` (`name`,`address`,`latitude`,`longitude`) VALUES
('Centro de distribución SECAP','Villanueva 1324, Belgrano, CABA',-34.563100,-58.453200);


INSERT INTO `zones` (`zone_name`,`range_description`,`description`) VALUES
('Muy Cerca','0-5 km','Zona inmediata al centro de distribución'),
('CABA','5-12 km','Ciudad Autónoma de Buenos Aires'),
('GBA Zona Norte','12-20 km','Gran Buenos Aires - Zona Norte (primer cinturón)'),
('GBA Alejado','>20 km','Gran Buenos Aires - Zona Norte alejada');


INSERT INTO `destinations` (`region`,`name`,`distance_km`,`zone_name`) VALUES
('caba','Agronomía',7,'CABA'),
('caba','Almagro',9,'CABA'),
('caba','Balvanera',10,'CABA'),
('caba','Barracas',14,'GBA Zona Norte'),
('caba','Belgrano',2,'Muy Cerca'),
('caba','Boedo',11,'CABA'),
('caba','Caballito',9,'CABA'),
('caba','Chacarita',5,'Muy Cerca'),
('caba','Coghlan',3,'Muy Cerca'),
('caba','Colegiales',3,'Muy Cerca'),
('caba','Constitución',12,'CABA'),
('caba','Flores',11,'CABA'),
('caba','Floresta',11,'CABA'),
('caba','La Boca',15,'GBA Zona Norte'),
('caba','Liniers',14,'GBA Zona Norte'),
('caba','Mataderos',16,'GBA Zona Norte'),
('caba','Monte Castro',13,'GBA Zona Norte'),
('caba','Monserrat',11,'CABA'),
('caba','Nueva Pompeya',13,'GBA Zona Norte'),
('caba','Núñez',2,'Muy Cerca'),
('caba','Once',10,'CABA'),
('caba','Palermo',4,'Muy Cerca'),
('caba','Parque Avellaneda',13,'GBA Zona Norte'),
('caba','Parque Chacabuco',11,'CABA'),
('caba','Parque Chas',5,'Muy Cerca'),
('caba','Parque Patricios',12,'CABA'),
('caba','Paternal',7,'CABA'),
('caba','Puerto Madero',11,'CABA'),
('caba','Recoleta',7,'CABA'),
('caba','Retiro',8,'CABA'),
('caba','Saavedra',5,'Muy Cerca'),
('caba','San Cristóbal',11,'CABA'),
('caba','San Nicolás',10,'CABA'),
('caba','San Telmo',12,'CABA'),
('caba','Versalles',13,'GBA Zona Norte'),
('caba','Villa Crespo',6,'CABA'),
('caba','Villa del Parque',8,'CABA'),
('caba','Villa Devoto',10,'CABA'),
('caba','Villa Gral. Mitre',8,'CABA'),
('caba','Villa Lugano',17,'GBA Zona Norte'),
('caba','Villa Luro',12,'CABA'),
('caba','Villa Ortúzar',4,'Muy Cerca'),
('caba','Villa Pueyrredón',6,'CABA'),
('caba','Villa Real',9,'CABA'),
('caba','Villa Riachuelo',18,'GBA Zona Norte'),
('caba','Villa Santa Rita',10,'CABA'),
('caba','Villa Soldati',14,'GBA Zona Norte'),
('caba','Villa Urquiza',5,'Muy Cerca'),
('gba_norte','Acassuso',10,'CABA'),
('gba_norte','Béccar',9,'CABA'),
('gba_norte','Caseros',12,'CABA'),
('gba_norte','Castelar',20,'GBA Zona Norte'),
('gba_norte','Ciudadela',15,'GBA Zona Norte'),
('gba_norte','Don Torcuato',24,'GBA Alejado'),
('gba_norte','El Palomar',18,'GBA Zona Norte'),
('gba_norte','General Pacheco',28,'GBA Alejado'),
('gba_norte','Haedo',17,'GBA Zona Norte'),
('gba_norte','Hurlingham',16,'GBA Zona Norte'),
('gba_norte','Ituzaingó',19,'GBA Zona Norte'),
('gba_norte','La Lucila',5,'Muy Cerca'),
('gba_norte','Martínez',6,'CABA'),
('gba_norte','Morón',17,'GBA Zona Norte'),
('gba_norte','Olivos',4,'Muy Cerca'),
('gba_norte','Ramos Mejía',14,'GBA Zona Norte'),
('gba_norte','San Fernando',16,'GBA Zona Norte'),
('gba_norte','San Isidro',8,'CABA'),
('gba_norte','San Martín',14,'GBA Zona Norte'),
('gba_norte','Santos Lugares',13,'GBA Zona Norte'),
('gba_norte','Tigre',26,'GBA Alejado'),
('gba_norte','Tres de Febrero',11,'CABA'),
('gba_norte','Vicente López',3,'Muy Cerca'),
('gba_norte','Villa Ballester',13,'GBA Zona Norte');


INSERT INTO `metadata` (`project`,`university`,`team`,`program`,`last_updated`,`total_destinations`) VALUES
('SECAP - Servicio de Cálculo de Envíos de Paquetes','Universidad de Belgrano','Grupo N° 2','Tecnicatura en Programación de Computadoras','2025-10-11',72);
