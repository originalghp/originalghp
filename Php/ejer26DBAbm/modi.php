<?php
/**
 * Vista: modi.php
 * Patron MVT - Modulo de Vista con acceso al Modelo
 * Actualiza contrato existente en la base de datos
 * Segun PDF ApuntePhpParte4CRUD_2.pdf paginas 19-21
 */

// Incluir archivo con datos de conexion
include('datosConexionBase.php');

$respuesta_estado = "Proceso de Modificacion de Contrato:\n";

// 1. RECEPCION DE DATOS DEL FORMULARIO (PDF pagina 19)
$ID_Contratos = $_POST['ID_Contratos'];
$DNI_Deudor = $_POST['DNI_Deudor'];
$Apellido_Nombres = $_POST['Apellido_Nombres'];
$Monto_total_financiado = $_POST['Monto_total_financiado'];
$FechaContrato = $_POST['FechaContrato'];
$NroDeCuotas = $_POST['NroDeCuotas'];

$respuesta_estado .= "Datos recibidos:\n";
$respuesta_estado .= "ID: $ID_Contratos\n";
$respuesta_estado .= "DNI: $DNI_Deudor\n";
$respuesta_estado .= "Nombre: $Apellido_Nombres\n";
$respuesta_estado .= "Monto: $Monto_total_financiado\n";
$respuesta_estado .= "Fecha: $FechaContrato\n";
$respuesta_estado .= "Cuotas: $NroDeCuotas\n";

// 2. CONEXION CON LA BASE DE DATOS
try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $dbh = new PDO($dsn, $user, $password);
    $respuesta_estado .= "Conexion exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado .= "Error en conexion: " . $e->getMessage() . "\n";
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error conexion en modi: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    echo $respuesta_estado;
    exit();
}

// 3. SQL DE ACTUALIZACION (PDF pagina 19)
// Es conveniente NO incluir los campos binarios dentro de la sentencia de modi
$sql = "UPDATE ContratoDeCuotas SET ";
$sql .= "DNI_Deudor = :DNI_Deudor, ";
$sql .= "Apellido_Nombres = :Apellido_Nombres, ";
$sql .= "Monto_total_financiado = :Monto_total_financiado, ";
$sql .= "FechaContrato = :FechaContrato, ";
$sql .= "NroDeCuotas = :NroDeCuotas ";
$sql .= "WHERE ID_Contratos = :ID_Contratos";

// 4. PREPARACION DE LA SENTENCIA (PDF pagina 19)
try {
    $stmt = $dbh->prepare($sql);
    $respuesta_estado .= "Preparacion exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado .= "Error en preparacion: " . $e->getMessage() . "\n";
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error preparacion en modi: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    echo $respuesta_estado;
    exit();
}

// 5. VINCULACION DE PARAMETROS (PDF pagina 19)
try {
    $stmt->bindParam(':ID_Contratos', $ID_Contratos);
    $stmt->bindParam(':DNI_Deudor', $DNI_Deudor);
    $stmt->bindParam(':Apellido_Nombres', $Apellido_Nombres);
    $stmt->bindParam(':Monto_total_financiado', $Monto_total_financiado);
    $stmt->bindParam(':FechaContrato', $FechaContrato);
    $stmt->bindParam(':NroDeCuotas', $NroDeCuotas);
    $respuesta_estado .= "Vinculacion exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado .= "Error en vinculacion: " . $e->getMessage() . "\n";
    echo $respuesta_estado;
    exit();
}

// 6. EJECUCION DE LA SENTENCIA (PDF pagina 19)
try {
    $stmt->execute();
    $respuesta_estado .= "Ejecucion exitosa - Contrato actualizado\n";
} catch (PDOException $e) {
    $respuesta_estado .= "Error en ejecucion: " . $e->getMessage() . "\n";
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error ejecucion en modi: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    echo $respuesta_estado;
    exit();
}

// 7. ACTUALIZACION DE ATRIBUTOS BINARIOS (PDF pagina 20)
// Es conveniente actualizar los atributos binarios luego de producida la modi
if (isset($_FILES['QR']) && !empty($_FILES['QR']['name'])) {
    $respuesta_estado .= "\nProcesando nueva imagen QR:\n";
    
    // Verificar que el archivo se subio correctamente
    if ($_FILES['QR']['error'] === UPLOAD_ERR_OK) {
        // Leer contenido del archivo temporal (PDF LecturaYActualizacionBinarios pagina 8)
        $contenidoQR = file_get_contents($_FILES['QR']['tmp_name']);
        
        $respuesta_estado .= "Archivo QR leido: " . $_FILES['QR']['name'] . "\n";
        $respuesta_estado .= "  Tamanio: " . strlen($contenidoQR) . " bytes\n";
        
        // SQL para actualizar el campo QR (PDF pagina 20)
        $sql = "UPDATE ContratoDeCuotas SET QR = :contenidoQR WHERE ID_Contratos = :ID_Contratos";
        
        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':contenidoQR', $contenidoQR, PDO::PARAM_LOB);
            $stmt->bindParam(':ID_Contratos', $ID_Contratos);
            $stmt->execute();
            
            $respuesta_estado .= "Imagen QR actualizada correctamente\n";
        } catch (PDOException $e) {
            $respuesta_estado .= "Error al actualizar QR: " . $e->getMessage() . "\n";
            
            // Log de errores
            $puntero = fopen("./errores.log", "a");
            fwrite($puntero, date("Y-m-d H:i") . " ");
            fwrite($puntero, "Error actualizar QR en modi: " . $e->getMessage());
            fwrite($puntero, "\n");
            fclose($puntero);
        }
    } else {
        $respuesta_estado .= "Error al subir archivo QR: codigo " . $_FILES['QR']['error'] . "\n";
    }
} else {
    $respuesta_estado .= "\ No se cambio la imagen QR (se mantuvo la anterior)\n";
}

// 8. CERRAR CONEXION (PDF pagina 21)
$dbh = null;

$respuesta_estado .= "\n===========================================\n";
$respuesta_estado .= "MODIFICACION COMPLETADA EXITOSAMENTE\n";
$respuesta_estado .= "===========================================\n";

// 9. ENVIAR RESPUESTA AL CLIENTE
echo $respuesta_estado;
?>