<?php
// Mostrar errores para debugging (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar que se recibieron los datos (PDF PHP Parte 2 - Página 1)
if (isset($_POST['codigoUsuario']) && isset($_POST['apellidoUsuario']) && isset($_POST['nombreUsuario'])) {
    
    // Leer datos del formulario (PDF PHP Parte 1 - Página 13)
    $codigoUsuario = $_POST['codigoUsuario'];
    $apellidoUsuario = $_POST['apellidoUsuario'];
    $nombreUsuario = $_POST['nombreUsuario'];

    // Crear objeto con los datos (PDF PHP Parte 1 - Página 9)
    $objUsuario = new stdClass();
    $objUsuario->RegistroCodUsuario = $codigoUsuario;
    $objUsuario->RegistroApellido = $apellidoUsuario;
    $objUsuario->RegistroNombre = $nombreUsuario;

    // Convertir a JSON (PDF PHP Parte 1 - Página 12)
    $jsonUsuario = json_encode($objUsuario);

    // Mostrar JSON (PDF PHP Parte 1 - Página 12)
    echo $jsonUsuario;
    
} else {
    echo "Error: No se recibieron los datos del formulario";
}
?>