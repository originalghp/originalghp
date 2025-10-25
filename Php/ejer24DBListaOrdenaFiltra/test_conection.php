<?php
include('datosConexionBase.php');

echo "<h2>Probando conexión a MySQL en Hostinger</h2>";
echo "<p>Base de datos: $dbname</p>";
echo "<p>Host: $host</p>";
echo "<p>Usuario: $user</p>";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3 style='color:green;'>✅ CONEXIÓN EXITOSA</h3>";
    
    // Probar consulta
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM ContratoDeCuotas");
    $result = $stmt->fetch();
    echo "<p>Total de contratos en la base de datos: " . $result['total'] . "</p>";
    
} catch (PDOException $e) {
    echo "<h3 style='color:red;'>❌ ERROR DE CONEXIÓN</h3>";
    echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Código:</strong> " . $e->getCode() . "</p>";
}
?>