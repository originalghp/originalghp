<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Form to Encrypt</title>
</head>
<body>
<?php
// Verificar si se recibió el formulario
if (isset($_POST['clave'])) {
    // Procesar la encriptación
    $claveOriginal = $_POST['clave'];
    
    // Encriptar con MD5
    $claveMd5 = md5($claveOriginal);
    
    // Encriptar con SHA1
    $claveSha1 = sha1($claveOriginal);
    
    // Mostrar resultados
    echo "<p><strong>Clave:</strong> " . htmlspecialchars($claveOriginal) . "</p>";
    echo "<p><strong>Clave encriptada en md5</strong> (128 bits o 16 octetos o 16 pares hexadecimales):</p>";
    echo "<p>" . $claveMd5 . "</p>";
    echo "<br>";
    
    echo "<p><strong>Clave:</strong> " . htmlspecialchars($claveOriginal) . "</p>";
    echo "<p><strong>Clave encriptada en sha1</strong> (160 bits o 20 octetos o 20 pares hexadecimales):</p>";
    echo "<p>" . $claveSha1 . "</p>";
    echo "<br>";
    
    echo "<a href='index.php'>Encriptar otra clave</a>";
    
} else {
    // Mostrar formulario
?>
    <p>Ingrese la clave a encriptar:</p>
    <form method="post" action="index.php">
        <input type="text" name="clave" required>
        <button type="submit">Obtener encriptación</button>
    </form>
<?php
}
?>
</body>
</html>