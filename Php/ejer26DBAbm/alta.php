<?php
/**
 * Vista: alta.php
 * Patron MVT - Modulo de Vista con acceso al Modelo
 * Inserta nuevo contrato en la base de datos
 * Segun PDF ApuntePhpParte4CRUD_2.pdf paginas 15-17
 */

// Incluir archivo con datos de conexion
include('datosConexionBase.php');

$respuesta_estado = "Proceso de Alta de Contrato:\n";

// 1. RECEPCION DE DATOS DEL FORMULARIO (PDF pagina 15)
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
    $respuesta_estado .= "Conexión exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado .= "Error en conexion: " . $e->getMessage() . "\n";
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error conexion en alta: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    echo $respuesta_estado;
    exit();
}

// 3. SQL DE INSERCION (PDF pagina 15)
// Es conveniente NO incluir los campos binarios dentro de la sentencia de alta
$sql = "INSERT INTO ContratoDeCuotas (ID_Contratos, DNI_Deudor, Apellido_Nombres, ";
$sql .= "Monto_total_financiado, FechaContrato, NroDeCuotas) ";
$sql .= "VALUES (:ID_Contratos, :DNI_Deudor, :Apellido_Nombres, ";
$sql .= ":Monto_total_financiado, :FechaContrato, :NroDeCuotas)";

// 4. PREPARACION DE LA SENTENCIA (PDF pagina 15)
try {
    $stmt = $dbh->prepare($sql);
    $respuesta_estado .= "Preparacion exitosa\n";
} catch (PDOException $e) {
    $respuesta_estado .= "Error en preparacion: " . $e->getMessage() . "\n";
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error preparacion en alta: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    echo $respuesta_estado;
    exit();
}

// 5. VINCULACION DE PARAMETROS (PDF pagina 16)
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

// 6. EJECUCION DE LA SENTENCIA (PDF pagina 16)
try {
    $stmt->execute();
    $respuesta_estado .= "Ejecucion exitosa - Contrato insertado\n";
} catch (PDOException $e) {
    $respuesta_estado .= "Error en ejecucion: " . $e->getMessage() . "\n";
    
    // Log de errores
    $puntero = fopen("./errores.log", "a");
    fwrite($puntero, date("Y-m-d H:i") . " ");
    fwrite($puntero, "Error ejecucion en alta: " . $e->getMessage());
    fwrite($puntero, "\n");
    fclose($puntero);
    
    echo $respuesta_estado;
    exit();
}

// 7. ACTUALIZACION DE ATRIBUTOS BINARIOS (PDF pagina 17)
// Es conveniente actualizar los atributos binarios luego de producida el alta
if (isset($_FILES['QR']) && !empty($_FILES['QR']['name'])) {
    $respuesta_estado .= "\nProcesando imagen QR:\n";
    
    // Verificar que el archivo se subio correctamente
    if ($_FILES['QR']['error'] === UPLOAD_ERR_OK) {
        // Leer contenido del archivo temporal (PDF LecturaYActualizacionBinarios pagina 8)
        $contenidoQR = file_get_contents($_FILES['QR']['tmp_name']);
        
        $respuesta_estado .= "Archivo QR leido: " . $_FILES['QR']['name'] . "\n";
        $respuesta_estado .= "  Tamanio: " . strlen($contenidoQR) . " bytes\n";
        
        // SQL para actualizar el campo QR (PDF pagina 17)
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
            fwrite($puntero, "Error actualizar QR en alta: " . $e->getMessage());
            fwrite($puntero, "\n");
            fclose($puntero);
        }
    } else {
        $respuesta_estado .= "Error al subir archivo QR: codigo " . $_FILES['QR']['error'] . "\n";
    }
} else {
    $respuesta_estado .= "\n No se subio imagen QR (campo opcional)\n";
}

// 8. CERRAR CONEXION (PDF pagina 18)
$dbh = null;

$respuesta_estado .= "\n=================================\n";
$respuesta_estado .= "ALTA COMPLETADA EXITOSAMENTE\n";
$respuesta_estado .= "=================================\n";

// 9. ENVIAR RESPUESTA AL CLIENTE
echo $respuesta_estado;
?>