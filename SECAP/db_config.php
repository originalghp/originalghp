<?php

// Configuración de la base de datos

$db_host = 'localhost';$db_host = 'localhost';

$db_user = 'root';$db_user = 'root';

$db_password = '';$db_password = '';

$db_name = 'secap';$db_name = 'secap';



// Crear conexión

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);$conn = new mysqli($db_host, $db_user, $db_password, $db_name);



// Verificar conexión

if ($conn->connect_error) {if ($conn->connect_error) {

    die("Error de conexión: " . $conn->connect_error);    die("Error de conexión: " . $conn->connect_error);

}}



// Establecer charset

$conn->set_charset("utf8mb4");$conn->set_charset("utf8mb4");

?>