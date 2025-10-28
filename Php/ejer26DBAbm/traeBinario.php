<?php
/**
 * Vista: traeBinario.php
 * Patron MVT - Modulo de Vista con acceso al Modelo
 * Lee imagen QR de la base de datos y la entrega codificada en base64
 * Segun PDF LecturaYActualizacionBinarios_1.pdf paginas 23-24
 */

// Incluir archivo con datos de conexion
include('datosConexionBase.php');

$respuesta_estado = "";

// 1. RECEPCION DE VARIABLES DEL REQUERIMIENTO
$ID_Contratos = $_POST['ID_Contratos'];

$respuesta_estado = $respuesta_estado . "Solicitando QR del contrato: " . $ID_Contratos . "\n";

// 2. APERTURA DE CONEXION CON EL MOTOR DE BASE DE DATOS
try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $dbh = new PDO($dsn, $user, $password);
    $respuesta_estado = $respuesta_estado . "Conexión exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "Error en conexión: " . $e->getMessage();
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error conexión en traeBinario: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    // Enviar error en JSON
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error de conexión";
    echo json_encode($objError);
    exit();
}

// 3. SENTENCIA SQL PARA TRAER EL QR (PDF LecturaYActualizacionBinarios pagina 23)
$sql = "SELECT QR FROM ContratoDeCuotas WHERE ID_Contratos = :ID_Contratos";

$respuesta_estado = $respuesta_estado . "Sentencia SQL a ser aplicada: " . $sql . "\n";

try {
    // 4. PREPARACION DE LA SENTENCIA (PDF pagina 23)
    $stmt = $dbh->prepare($sql);
    $respuesta_estado = $respuesta_estado . "Preparación exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "Error en preparación: " . $e->getMessage();
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error preparación en traeBinario: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    // Enviar error en JSON
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error en preparación";
    echo json_encode($objError);
    exit();
}

// 5. VINCULACION DE PARAMETROS
try {
    $stmt->bindParam(':ID_Contratos', $ID_Contratos);
    $respuesta_estado = $respuesta_estado . "Vinculación exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "Error en vinculación: " . $e->getMessage();
    
    // Enviar error en JSON
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error en vinculación";
    echo json_encode($objError);
    exit();
}

// 6. CONFIGURAR MODO DE FETCH Y EJECUTAR
try {
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $respuesta_estado = $respuesta_estado . "Ejecución exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "Error en ejecución: " . $e->getMessage();
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error ejecución en traeBinario: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    // Enviar error en JSON
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error en ejecución";
    echo json_encode($objError);
    exit();
}

// 7. OBTENER LOS RESULTADOS (PDF LecturaYActualizacionBinarios pagina 23)
// Obtengo los resultados de cada fila (en este caso debería ser una sola)
$fila = $stmt->fetch();

if ($fila) {
    // 8. CREAR OBJETO PARA ENVIAR (PDF pagina 24)
    $objContrato = new stdClass();
    
    // 9. CODIFICAR QR EN BASE64 (PDF LecturaYActualizacionBinarios pagina 24)
    // Como QR es LONGBLOB, debe codificarse en base64 para transferir por la red
    // evitando que caracteres ASCII como >, <, !, etc. se confundan con etiquetas HTML
    if ($fila['QR'] !== null && $fila['QR'] !== '') {
        $objContrato->QR = base64_encode($fila['QR']);
        $respuesta_estado = $respuesta_estado . "QR codificado en base64 correctamente\n";
        $respuesta_estado = $respuesta_estado . "Tamaño del QR: " . strlen($fila['QR']) . " bytes\n";
    } else {
        $objContrato->QR = '';
        $respuesta_estado = $respuesta_estado . "El contrato no tiene imagen QR\n";
    }
    
    $objContrato->estado = $respuesta_estado;
    
    // 10. CODIFICAR EN JSON (PDF pagina 24)
    // Usar JSON_INVALID_UTF8_SUBSTITUTE para cuidar que la conversion a JSON
    // no genere caracteres UTF-8 que estén fuera del rango base64
    $salidaJson = json_encode($objContrato, JSON_INVALID_UTF8_SUBSTITUTE);
    
} else {
    // No se encontró el contrato
    $respuesta_estado = $respuesta_estado . "No se encontró el contrato con ID: " . $ID_Contratos . "\n";
    
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Contrato no encontrado";
    $objError->estado = $respuesta_estado;
    $salidaJson = json_encode($objError, JSON_INVALID_UTF8_SUBSTITUTE);
}

// 11. CERRAR CONEXION (PDF pagina 24)
$dbh = null;

// 12. ENVIAR RESPUESTA AL CLIENTE
header('Content-Type: application/json');
echo $salidaJson;
?>