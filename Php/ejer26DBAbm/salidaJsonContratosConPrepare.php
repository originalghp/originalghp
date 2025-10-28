<?php
/**
 * Vista: salidaJsonContratosConPrepare.php
 * Patron MVT - Modulo de Vista con acceso al Modelo
 * Entrega datos de contratos en formato JSON usando sentencias preparadas
 * Segun PDF paginas 7-14
 * MODIFICADO: Maneja campo QR como LONGBLOB (PDF Lectura y Actualizacion de Binarios)
 */

// Incluir archivo con datos de conexion (PDF pagina 7)
include('datosConexionBase.php');

// Variables para manejo de errores
$respuesta_estado = "";

// 1. RECEPCION DE VARIABLES DEL REQUERIMIENTO (PDF pagina 7)
$orden = isset($_POST['orden']) ? $_POST['orden'] : 'ID_Contratos';
$f_NroDeCuotas = isset($_POST['f_NroDeCuotas']) ? $_POST['f_NroDeCuotas'] : '';

// 2. APERTURA DE CONEXION CON EL MOTOR DE BASE DE DATOS (PDF pagina 8)
try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $dbh = new PDO($dsn, $user, $password); /*Database Handler*/
    $respuesta_estado = $respuesta_estado . "\nConexion exitosa";
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "\nError en la conexion: " . $e->getMessage();
    
    // Escribir en log de errores (PDF pagina 14)
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error conexion: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    // Enviar respuesta de error al cliente
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error de conexion a la base de datos";
    echo json_encode($objError);
    exit();
}

// 3. ASIGNACION DE CONSULTA SQL A UNA VARIABLE (PDF pagina 9 y 12)
$sql = "SELECT * FROM ContratoDeCuotas WHERE ";

// Aplicar filtro si existe (PDF pagina 12 - uso de parametros preparados)
if ($f_NroDeCuotas !== '') {
    $sql = $sql . "NroDeCuotas = :NroDeCuotas ";
} else {
    // Si no hay filtro, usar condicion siempre verdadera
    $sql = $sql . "1=1 ";
}

// Agregar ordenamiento (PDF pagina 9)
$sql = $sql . "ORDER BY " . $orden;

try {
    // 4. PREPARACION, VINCULACION Y EJECUCION DE SENTENCIA SQL (PDF pagina 9 y 12-13)
    
    // Preparacion de la sentencia. El metodo crea un objeto sentencia (PDF pagina 9)
    $stmt = $dbh->prepare($sql);
    
    // Vinculacion de sentencia (PDF pagina 12)
    if ($f_NroDeCuotas !== '') {
        $stmt->bindParam(':NroDeCuotas', $f_NroDeCuotas);
    }
    
    // Metodo para fijar el tipo de variable que devolveria la ejecucion (PDF pagina 9)
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
    // Ejecucion de la sentencia (PDF pagina 9 y 13)
    $stmt->execute();
    
    $respuesta_estado = $respuesta_estado . "\nEjecucion exitosa";
    
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "\nError en ejecucion: " . $e->getMessage();
    
    // Escribir en log de errores (PDF pagina 14)
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error ejecucion SQL: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    // Enviar respuesta de error al cliente
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error al ejecutar consulta";
    echo json_encode($objError);
    exit();
}

// 5. CONSTRUCCION DE RESPUESTA EN UNA VARIABLE DE TIPO OBJETO (PDF pagina 10)

// Ahora creamos un arreglo vacio para almacenar los datos obtenidos de la consulta (PDF pagina 10)
$contratos = [];

// El metodo fetch aplicado al objeto $stmt asigna cada fila de la consulta a una variable 
// de tipo arreglo asociativo $fila['nombreAtributo'] (PDF pagina 10)
while($fila = $stmt->fetch()) {
    // Luego creo un objeto para representar cada elemento del array obtenido (PDF pagina 10)
    // En este caso un contrato y realizo las asignaciones correspondientes
    $objContrato = new stdClass();
    $objContrato->ID_Contratos = $fila['ID_Contratos'];
    $objContrato->DNI_Deudor = $fila['DNI_Deudor'];
    $objContrato->Apellido_Nombres = $fila['Apellido_Nombres'];
    $objContrato->Monto_total_financiado = $fila['Monto_total_financiado'];
    
    // Convertir fecha de YYYY-MM-DD a DD-MM-YYYY para mostrar
    $fecha = $fila['FechaContrato'];
    if ($fecha) {
        $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
        if ($fechaObj) {
            $objContrato->FechaContrato = $fechaObj->format('d-m-Y');
        } else {
            $objContrato->FechaContrato = $fecha;
        }
    } else {
        $objContrato->FechaContrato = '';
    }
    
    $objContrato->NroDeCuotas = $fila['NroDeCuotas'];
    
    // MODIFICACION: Codificar QR en base64 si existe 
    // (PDF Lectura y Actualizacion de Binarios pagina 4)
    // Como QR ahora es LONGBLOB, debe codificarse en base64 para transferir por la red
    // evitando que caracteres ASCII como >, <, !, etc. se confundan con etiquetas HTML
    if ($fila['QR'] !== null && $fila['QR'] !== '') {
        $objContrato->QR = base64_encode($fila['QR']);
    } else {
        $objContrato->QR = '';
    }
    
    // Claramente el array de contratos se puebla en cada ciclo del while (PDF pagina 10)
    array_push($contratos, $objContrato);
}

// Finalmente podemos si lo deseamos, crear un objeto que represente al arreglo completo
// de filas de la consulta (PDF pagina 11)
$objContratos = new stdClass();
// Al primer atributo de mi objeto le asigno el array completo de filas de la consulta (PDF pagina 11)
$objContratos->contratos = $contratos;
// Al segundo atributo de mi objeto le asigno por ejemplo el numero de filas obtenidas (PDF pagina 11)
$objContratos->cuenta = count($contratos);
$objContratos->estado = $respuesta_estado;

// 6. CONVERSION DE LA VARIABLE OBJETO DE PHP EN UNA CADENA JSON (PDF pagina 11)
// MODIFICACION: Usar JSON_INVALID_UTF8_SUBSTITUTE para cuidar que la conversion a JSON
// no genere caracteres UTF-8 que esten fuera del rango base64
// (PDF Lectura y Actualizacion de Binarios pagina 4)
$salidaJson = json_encode($objContratos, JSON_INVALID_UTF8_SUBSTITUTE);

// Cierro la conexion con la base de datos (PDF pagina 11)
$dbh = null;

// 7. ENVIO DE LA RESPUESTA AL CLIENTE REMOTO (PDF pagina 11)
// Envio JSON al navegador remoto para que nuestra presentacion construida en HTML JS y CSS 
// muestre los datos de la manera deseada
header('Content-Type: application/json');
echo $salidaJson;
?>