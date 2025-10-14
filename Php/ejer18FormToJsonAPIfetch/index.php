<?php
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
?>