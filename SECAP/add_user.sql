<?php
// Configuración de la base de datos
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'secap';

// Crear conexión
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer charset
$conn->set_charset("utf8mb4");
?>

USE secap;

-- Crear tabla de usuarios si no existe
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'operador'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar usuario admin (si no existe)
INSERT IGNORE INTO users (username, password, role) VALUES ('admin', 'secap2025', 'admin');
-- Insertar usuario operador
INSERT IGNORE INTO users (username, password, role) VALUES ('operador', 'operador2025', 'operador');
