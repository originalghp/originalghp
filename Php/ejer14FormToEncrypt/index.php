<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Form to Encrypt</title>
</head>
<body>
<?php

if (isset($_POST['clave'])) {
    $claveAEncriptar = $_POST['clave'];
    $claveMd5 = md5($claveAEncriptar);
    $claveSha1 = sha1($claveAEncriptar);
    
    echo "<p>Clave: " . $claveAEncriptar . "</p>";
    echo "<p>Clave encriptada en md5 (128 bits o 16 octetos o 16 pares hexadecimales):</p>";
    echo "<p>" . $claveMd5 . "</p>";
    echo "<br/>";
    
    echo "<p>Clave: " . $claveAEncriptar . "</p>";
    echo "<p>Clave encriptada en sha1 (160 bits o 20 octetos o 20 pares hexadecimales):</p>";
    echo "<p>" . $claveSha1 . "</p>";
    
} else {
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