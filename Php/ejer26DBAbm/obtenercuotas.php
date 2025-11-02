<?php
/**
 * Vista: obtenerCuotas.php
 * Patron MVT - Modulo de Vista con acceso al Modelo
 * Entrega datos de la tabla Cuotas en formato JSON
 * Utilizado para poblar el filtro de cuotas dinamicamente
 */

// Incluir archivo con datos de conexion
include('datosConexionBase.php');

$respuesta_estado = "";

// 1. APERTURA DE CONEXION CON EL MOTOR DE BASE DE DATOS
try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $dbh = new PDO($dsn, $user, $password);
    $respuesta_estado = $respuesta_estado . "\nConexion exitosa";
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "\nError en la conexion: " . $e->getMessage();
    
    // Escribir en log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error conexion en obtenerCuotas: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    // Enviar respuesta de error al cliente
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error de conexion a la base de datos";
    echo json_encode($objError);
    exit();
}

// 2. ASIGNACION DE CONSULTA SQL A UNA VARIABLE
$sql = "SELECT Cod, Descrip FROM Cuotas ORDER BY Cod";

try {
    // 3. PREPARACION Y EJECUCION DE SENTENCIA SQL
    $stmt = $dbh->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    
    $respuesta_estado = $respuesta_estado . "\nEjecucion exitosa";
    
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "\nError en ejecucion: " . $e->getMessage();
    
    // Escribir en log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error ejecucion SQL en obtenerCuotas: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    // Enviar respuesta de error al cliente
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error al ejecutar consulta";
    echo json_encode($objError);
    exit();
}

// 4. CONSTRUCCION DE RESPUESTA EN UNA VARIABLE DE TIPO OBJETO
$cuotas = [];

while($fila = $stmt->fetch()) {
    $objCuota = new stdClass();
    $objCuota->Cod = $fila['Cod'];
    $objCuota->Descrip = $fila['Descrip'];
    
    array_push($cuotas, $objCuota);
}

// Crear objeto para la respuesta
$objCuotas = new stdClass();
$objCuotas->cuotas = $cuotas;
$objCuotas->cuenta = count($cuotas);
$objCuotas->estado = $respuesta_estado;

// 5. CONVERSION A JSON
$salidaJson = json_encode($objCuotas);

// Cerrar la conexion con la base de datos
$dbh = null;

// 6. ENVIO DE LA RESPUESTA AL CLIENTE REMOTO
header('Content-Type: application/json');
echo $salidaJson;
?>