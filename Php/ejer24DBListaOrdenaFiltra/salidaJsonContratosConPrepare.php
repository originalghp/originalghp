<?php
/**
 * Vista: salidaJsonContratosConPrepare.php
 * Patrón MVT - Módulo de Vista con acceso al Modelo
 * Entrega datos de contratos en formato JSON usando sentencias preparadas
 * Según PDF páginas 7-14
 */

// Incluir archivo con datos de conexión (PDF página 7)
include('datosConexionBase.php');

// Variables para manejo de errores
$respuesta_estado = "";

// 1. RECEPCIÓN DE VARIABLES DEL REQUERIMIENTO (PDF página 7)
$orden = isset($_POST['orden']) ? $_POST['orden'] : 'ID_Contratos';
$f_NroDeCuotas = isset($_POST['f_NroDeCuotas']) ? $_POST['f_NroDeCuotas'] : '';

// 2. APERTURA DE CONEXIÓN CON EL MOTOR DE BASE DE DATOS (PDF página 8)
try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $dbh = new PDO($dsn, $user, $password); /*Database Handler*/
    $respuesta_estado = $respuesta_estado . "\nConexión exitosa";
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "\nError en la conexión: " . $e->getMessage();
    
    // Escribir en log de errores (PDF página 14)
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

// 3. ASIGNACIÓN DE CONSULTA SQL A UNA VARIABLE (PDF página 9 y 12)
$sql = "SELECT * FROM ContratoDeCuotas WHERE ";

// Aplicar filtro si existe (PDF página 12 - uso de parámetros preparados)
if ($f_NroDeCuotas !== '') {
    $sql = $sql . "NroDeCuotas = :NroDeCuotas ";
} else {
    // Si no hay filtro, usar condición siempre verdadera
    $sql = $sql . "1=1 ";
}

// Agregar ordenamiento (PDF página 9)
$sql = $sql . "ORDER BY " . $orden;

try {
    // 4. PREPARACIÓN, VINCULACIÓN Y EJECUCIÓN DE SENTENCIA SQL (PDF página 9 y 12-13)
    
    // Preparación de la sentencia. El método crea un objeto sentencia (PDF página 9)
    $stmt = $dbh->prepare($sql);
    
    // Vinculación de sentencia (PDF página 12)
    if ($f_NroDeCuotas !== '') {
        $stmt->bindParam(':NroDeCuotas', $f_NroDeCuotas);
    }
    
    // Método para fijar el tipo de variable que devolvería la ejecución (PDF página 9)
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
    // Ejecución de la sentencia (PDF página 9 y 13)
    $stmt->execute();
    
    $respuesta_estado = $respuesta_estado . "\nEjecución exitosa";
    
} catch (PDOException $e) {
    $respuesta_estado = $respuesta_estado . "\nError en ejecución: " . $e->getMessage();
    
    // Escribir en log de errores (PDF página 14)
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

// 5. CONSTRUCCIÓN DE RESPUESTA EN UNA VARIABLE DE TIPO OBJETO (PDF página 10)

// Ahora creamos un arreglo vacío para almacenar los datos obtenidos de la consulta (PDF página 10)
$contratos = [];

// El método fetch aplicado al objeto $stmt asigna cada fila de la consulta a una variable 
// de tipo arreglo asociativo $fila['nombreAtributo'] (PDF página 10)
while($fila = $stmt->fetch()) {
    // Luego creo un objeto para representar cada elemento del array obtenido (PDF página 10)
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
    $objContrato->QR = $fila['QR'];
    
    // Claramente el array de contratos se puebla en cada ciclo del while (PDF página 10)
    array_push($contratos, $objContrato);
}

// Finalmente podemos si lo deseamos, crear un objeto que represente al arreglo completo
// de filas de la consulta (PDF página 11)
$objContratos = new stdClass();
// Al primer atributo de mi objeto le asigno el array completo de filas de la consulta (PDF página 11)
$objContratos->contratos = $contratos;
// Al segundo atributo de mi objeto le asigno por ejemplo el número de filas obtenidas (PDF página 11)
$objContratos->cuenta = count($contratos);
$objContratos->estado = $respuesta_estado;

// 6. CONVERSIÓN DE LA VARIABLE OBJETO DE PHP EN UNA CADENA JSON (PDF página 11)
// Finalmente codifico como texto JSON al objeto obtenido
$salidaJson = json_encode($objContratos);

// Cierro la conexión con la base de datos (PDF página 11)
$dbh = null;

// 7. ENVÍO DE LA RESPUESTA AL CLIENTE REMOTO (PDF página 11)
// Envío JSON al navegador remoto para que nuestra presentación construida en HTML JS y CSS 
// muestre los datos de la manera deseada
header('Content-Type: application/json');
echo $salidaJson;
?>