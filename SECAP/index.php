<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Redirigiendo a la interfaz...</title>
    <script>
        window.location.href = "Interfaz/index.html";
    </script>
</head>
<body>
    <p>Redirigiendo a la interfaz principal...</p>
</body>
</html>
