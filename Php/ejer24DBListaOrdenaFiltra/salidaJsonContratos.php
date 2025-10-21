<?php
/**
 * Vista: salidaJsonContratos.php
 * Patrón MVT - Módulo de Vista con acceso al Modelo
 * Entrega datos de contratos en formato JSON
 */

// Variables para manejo de errores
$respuesta_estado = "";
$fecha = date("Y-m-d H:i");

// 1. RECEPCIÓN DE VARIABLES DEL REQUERIMIENTO
$orden = isset($_POST['orden']) ? $_POST['orden'] : 'ID_Contratos';
$f_NroDeCuotas = isset($_POST['f_NroDeCuotas']) ? $_POST['f_NroDeCuotas'] : '';

// 2. APERTURA DE CONEXIÓN CON EL MOTOR DE BASE DE DATOS
$dbname = "Contratos_en_cuotas";
$host = "localhost";
$user = "root";
$password = "";

try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $respuesta_estado = $respuesta_estado . "\nConexión exitosa";
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "\nError en la conexión: " . $e->getMessage();
    
    // Escribir en log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error conexión: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    // Enviar respuesta de error al cliente
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error de conexión a la base de datos";
    echo json_encode($objError);
    exit();
}

// 3. ASIGNACIÓN DE CONSULTA SQL A UNA VARIABLE
$sql = "SELECT * FROM ContratoDeCuotas WHERE ";

// Aplicar filtro si existe
if ($f_NroDeCuotas !== '') {
    $sql = $sql . "NroDeCuotas = :NroDeCuotas ";
} else {
    // Si no hay filtro, usar condición siempre verdadera
    $sql = $sql . "1=1 ";
}

// Agregar ordenamiento
$sql = $sql . "ORDER BY " . $orden;

try {
    // 4. PREPARACIÓN, VINCULACIÓN Y EJECUCIÓN DE SENTENCIA SQL
    $stmt = $dbh->prepare($sql);
    
    // Vincular parámetros si hay filtro
    if ($f_NroDeCuotas !== '') {
        $stmt->bindParam(':NroDeCuotas', $f_NroDeCuotas);
    }
    
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    
    $respuesta_estado = $respuesta_estado . "\nEjecución exitosa";
    
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "\nError en ejecución: " . $e->getMessage();
    
    // Escribir en log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error ejecución SQL: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    // Enviar respuesta de error al cliente
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error al ejecutar consulta";
    echo json_encode($objError);
    exit();
}

// 5. CONSTRUCCIÓN DE RESPUESTA EN UNA VARIABLE DE TIPO OBJETO
$contratos = [];

while($fila = $stmt->fetch()) {
    $objContrato = new stdClass();
    $objContrato->ID_Contratos = $fila['ID_Contratos'];
    $objContrato->DNI_Deudor = $fila['DNI_Deudor'];
    $objContrato->Apellido_Nombres = $fila['Apellido_Nombres'];
    $objContrato->Monto_total_financiado = $fila['Monto_total_financiado'];
    $objContrato->FechaContrato = $fila['FechaContrato'];
    $objContrato->NroDeCuotas = $fila['NroDeCuotas'];
    $objContrato->QR = $fila['QR'];
    
    array_push($contratos, $objContrato);
}

// Crear objeto final con los contratos y metadatos
$objContratos = new stdClass();
$objContratos->contratos = $contratos;
$objContratos->cuenta = count($contratos);
$objContratos->estado = $respuesta_estado;

// 6. CONVERSIÓN DE LA VARIABLE OBJETO DE PHP EN UNA CADENA JSON
$salidaJson = json_encode($objContratos);

// Cerrar conexión
$dbh = null;

// 7. ENVÍO DE LA RESPUESTA AL CLIENTE REMOTO
header('Content-Type: application/json');
echo $salidaJson;
?>