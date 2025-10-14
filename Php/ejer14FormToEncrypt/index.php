<?php
// Verificar si se recibió el formulario con método POST
if (isset($_POST['clave'])) {
    // Leer la clave ingresada
    $claveOriginal = $_POST['clave'];
    
    // Encriptar con MD5 (128 bits = 16 pares hexadecimales)
    $claveMd5 = md5($claveOriginal);
    
    // Encriptar con SHA1 (160 bits = 20 pares hexadecimales)
    $claveSha1 = sha1($claveOriginal);
    
    // Calcular longitudes
    $longitudMd5 = strlen($claveMd5);
    $longitudSha1 = strlen($claveSha1);
    
    // Contar octetos (cada par hexadecimal = 1 byte = 1 octeto)
    $octetosMd5 = $longitudMd5 / 2;
    $octetosSha1 = $longitudSha1 / 2;
    
    // Mostrar resultados
    echo "<h3>Clave: " . htmlspecialchars($claveOriginal) . "</h3>";
    echo "<h3>Clave encriptada en md5: <span style='color:blue'>" . $claveMd5 . "</span> (" . $longitudMd5 . " bits o " . $octetosMd5 . " octetos o " . $octetosMd5 . " pares hexadecimales):</h3>";
    echo "<p>" . $claveMd5 . "</p>";
    
    echo "<h3>Clave: " . htmlspecialchars($claveOriginal) . "</h3>";
    echo "<h3>Clave encriptada en sha256: <span style='color:blue'>" . $claveSha1 . "</span> (256 bits o 32 octetos o 32 pares hexadecimales):</h3>";
    echo "<p>" . $claveSha1 . "</p>";
    
} else {
    // Si no se recibió POST, mostrar el formulario
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form to Encrypt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin: 20px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            padding: 5px;
            width: 300px;
            margin-bottom: 10px;
        }
        button {
            padding: 5px 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Ingrese la clave a encriptar:</h2>
    <form method="post" action="index.php">
        <label for="clave">Clave:</label>
        <input type="text" id="clave" name="clave" required>
        <br>
        <button type="submit">Obtener encriptación</button>
    </form>
</body>
</html>
<?php
}
?>