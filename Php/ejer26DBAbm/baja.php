<?php
/**
 * Vista: baja.php
 * Patron MVT - Modulo de Vista con acceso al Modelo
 * Elimina contrato de la base de datos
 * Segun PDF ApuntePhpParte4CRUD_2.pdf pagina 22
 */

// Incluir archivo con datos de conexion
include('datosConexionBase.php');

$respuesta_estado = "Proceso de Baja de Contrato:\n";

// 1. RECEPCION DE DATOS
$ID_Contratos = $_POST['ID_Contratos'];

$respuesta_estado .= "ID del contrato a eliminar: $ID_Contratos\n";

// 2. CONEXION CON LA BASE DE DATOS
try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $dbh = new PDO($dsn, $user, $password);
    $respuesta_estado .= "✓ Conexión exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado .= "✗ Error en conexión: " . $e->getMessage() . "\n";
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error conexión en baja: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    echo $respuesta_estado;
    exit();
}

// 3. SQL DE ELIMINACION (PDF pagina 22)
$sql = "DELETE FROM ContratoDeCuotas WHERE ID_Contratos = :ID_Contratos";

// 4. PREPARACION DE LA SENTENCIA
try {
    $stmt = $dbh->prepare($sql);
    $respuesta_estado .= "✓ Preparación exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado .= "✗ Error en preparación: " . $e->getMessage() . "\n";
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error preparación en baja: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    echo $respuesta_estado;
    exit();
}

// 5. VINCULACION DE PARAMETROS
try {
    $stmt->bindParam(':ID_Contratos', $ID_Contratos);
    $respuesta_estado .= "✓ Vinculación exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado .= "✗ Error en vinculación: " . $e->getMessage() . "\n";
    echo $respuesta_estado;
    exit();
}

// 6. EJECUCION DE LA SENTENCIA
try {
    $stmt->execute();
    
    // Verificar si se eliminó algún registro
    $rowCount = $stmt->rowCount();
    
    if ($rowCount > 0) {
        $respuesta_estado .= "✓ Ejecución exitosa - Contrato eliminado\n";
        $respuesta_estado .= "  Registros afectados: $rowCount\n";
    } else {
        $respuesta_estado .= "⚠ No se encontró el contrato con ID: $ID_Contratos\n";
    }
    
} catch (PDOException $e) {
    $respuesta_estado .= "✗ Error en ejecución: " . $e->getMessage() . "\n";
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error ejecución en baja: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    echo $respuesta_estado;
    exit();
}

// 7. CERRAR CONEXION (PDF pagina 22)
$dbh = null;

$respuesta_estado .= "\n=====================================\n";
$respuesta_estado .= "✅ BAJA COMPLETADA EXITOSAMENTE\n";
$respuesta_estado .= "=====================================\n";

// 8. ENVIAR RESPUESTA AL CLIENTE
echo $respuesta_estado;
?>