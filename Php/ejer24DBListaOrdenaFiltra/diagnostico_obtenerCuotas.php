<?php
/**
 * DIAGNOSTICO obtenerCuotas.php
 * Versión con más información de debug
 */

// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== INICIO DE DIAGNÓSTICO ===\n\n";

// Incluir archivo con datos de conexion
echo "1. Incluyendo datosConexionBase.php...\n";
include('datosConexionBase.php');
echo "   ✓ Archivo incluido\n\n";

echo "2. Datos de conexión:\n";
echo "   Host: $host\n";
echo "   Base de datos: $dbname\n";
echo "   Usuario: $user\n";
echo "   Password: " . (strlen($password) > 0 ? "[configurado]" : "[VACIO]") . "\n\n";

$respuesta_estado = "";

// APERTURA DE CONEXION
echo "3. Intentando conectar...\n";
try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $dbh = new PDO($dsn, $user, $password);
    echo "   ✓ Conexión exitosa\n\n";
    $respuesta_estado = "Conexion exitosa";
} catch (PDOException $e) {
    echo "   ✗ ERROR: " . $e->getMessage() . "\n\n";
    $respuesta_estado = "Error en la conexion: " . $e->getMessage();
    
    $objError = new stdClass();
    $objError->error = true;
    $objError->mensaje = "Error de conexion a la base de datos";
    $objError->detalle = $e->getMessage();
    
    header('Content-Type: application/json');
    echo "\n\n=== RESPUESTA JSON ===\n";
    echo json_encode($objError);
    exit();
}

// VERIFICAR QUE LA TABLA EXISTE
echo "4. Verificando que la tabla 'cuotas' existe...\n";
try {
    $stmt_check = $dbh->query("SHOW TABLES LIKE 'cuotas'");
    $tabla_existe = $stmt_check->fetch();
    
    if ($tabla_existe) {
        echo "   ✓ Tabla 'cuotas' encontrada\n\n";
    } else {
        echo "   ✗ Tabla 'cuotas' NO encontrada\n";
        echo "   Buscando con otras variaciones...\n";
        
        // Buscar Cuotas con mayúscula
        $stmt_check2 = $dbh->query("SHOW TABLES LIKE 'Cuotas'");
        $tabla_existe2 = $stmt_check2->fetch();
        
        if ($tabla_existe2) {
            echo "   ⚠ Encontrada como 'Cuotas' (con mayúscula)\n";
            echo "   SOLUCIÓN: Usa 'Cuotas' en lugar de 'cuotas'\n\n";
        } else {
            echo "   ✗ No se encontró ninguna variación de la tabla\n";
            echo "   SOLUCIÓN: Importa el archivo SQL\n\n";
        }
    }
} catch (PDOException $e) {
    echo "   ✗ Error al verificar tabla: " . $e->getMessage() . "\n\n";
}

// CONSULTA SQL
echo "5. Ejecutando consulta SQL...\n";
$sql = "SELECT Cod, Descrip FROM cuotas ORDER BY Cod";
echo "   SQL: $sql\n";

try {
    $stmt = $dbh->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    
    echo "   ✓ Consulta ejecutada\n\n";
    $respuesta_estado .= "\nEjecucion exitosa";
    
} catch (PDOException $e) {
    echo "   ✗ ERROR: " . $e->getMessage() . "\n";
    echo "   Mensaje: " . $e->getMessage() . "\n\n";
    
    // Si falla con minúsculas, probar con mayúsculas
    echo "6. Intentando con 'Cuotas' (mayúscula)...\n";
    $sql2 = "SELECT Cod, Descrip FROM Cuotas ORDER BY Cod";
    echo "   SQL: $sql2\n";
    
    try {
        $stmt = $dbh->prepare($sql2);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        echo "   ✓ ¡Funciona con mayúscula!\n";
        echo "   SOLUCIÓN: Usa 'Cuotas' en lugar de 'cuotas'\n\n";
    } catch (PDOException $e2) {
        echo "   ✗ Tampoco funciona con mayúscula\n";
        echo "   ERROR: " . $e2->getMessage() . "\n\n";
        
        $objError = new stdClass();
        $objError->error = true;
        $objError->mensaje = "Error al ejecutar consulta";
        $objError->detalle = $e2->getMessage();
        
        header('Content-Type: application/json');
        echo "\n=== RESPUESTA JSON ===\n";
        echo json_encode($objError);
        exit();
    }
}

// CONSTRUCCION DE RESPUESTA
echo "7. Construyendo respuesta JSON...\n";
$cuotas = [];

while($fila = $stmt->fetch()) {
    $objCuota = new stdClass();
    $objCuota->Cod = $fila['Cod'];
    $objCuota->Descrip = $fila['Descrip'];
    array_push($cuotas, $objCuota);
}

echo "   Registros encontrados: " . count($cuotas) . "\n";
foreach ($cuotas as $cuota) {
    echo "   - " . $cuota->Cod . ": " . $cuota->Descrip . "\n";
}
echo "\n";

// Crear objeto para la respuesta
$objCuotas = new stdClass();
$objCuotas->cuotas = $cuotas;
$objCuotas->cuenta = count($cuotas);
$objCuotas->estado = $respuesta_estado;

// CONVERSION A JSON
$salidaJson = json_encode($objCuotas);

// Cerrar conexion
$dbh = null;

echo "8. Enviando respuesta JSON...\n\n";
echo "=== RESPUESTA JSON ===\n";

// ENVIO DE LA RESPUESTA
header('Content-Type: application/json');
echo $salidaJson;
?>