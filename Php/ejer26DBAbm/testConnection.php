<?php
/**
 * TEST DE CONEXION A BASE DE DATOS - HOSTINGER
 * Archivo de diagnostico para verificar la conexion a MySQL
 * 
 * INSTRUCCIONES:
 * 1. Modifica las credenciales de conexion abajo segun tu configuracion de Hostinger
 * 2. Sube este archivo a tu servidor
 * 3. Accede via navegador: http://tudominio.com/testConnection.php
 * 4. Una vez verificada la conexion, ELIMINA este archivo por seguridad
 */

// ============================================
// CONFIGURACION DE CONEXION
// ============================================
// IMPORTANTE: Modifica estos valores con los datos de tu panel de Hostinger

$host = "localhost";  // En Hostinger suele ser "localhost"
$dbname = "u123456789_contratos";  // Reemplaza con el nombre de tu BD de Hostinger
$user = "u123456789_usuario";  // Reemplaza con tu usuario de BD de Hostinger
$password = "TuPasswordSeguro123";  // Reemplaza con tu password de Hostinger

// ============================================
// CONFIGURACION DE VISUALIZACION
// ============================================
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - Hostinger</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: #333;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.8;
            font-size: 14px;
        }
        
        .content {
            padding: 30px;
        }
        
        .test-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        
        .test-section h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }
        
        .warning {
            background: #fff3cd;
            color: #856404;
            border-left-color: #ffc107;
        }
        
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border-left-color: #17a2b8;
        }
        
        .result-item {
            padding: 10px;
            margin: 10px 0;
            background: white;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
        
        .result-item strong {
            color: #667eea;
            display: inline-block;
            min-width: 150px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 5px;
            overflow: hidden;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        
        tr:hover {
            background: #f5f5f5;
        }
        
        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Test de Conexión a Base de Datos</h1>
            <p>Diagnóstico para Hostinger - Sistema de Contratos en Cuotas</p>
        </div>
        
        <div class="content">
            <?php
            // ============================================
            // TEST 1: INFORMACION DEL SERVIDOR
            // ============================================
            echo '<div class="test-section info">';
            echo '<h2>📊 1. Información del Servidor</h2>';
            echo '<div class="result-item"><strong>PHP Version:</strong> ' . phpversion() . '</div>';
            echo '<div class="result-item"><strong>Servidor:</strong> ' . $_SERVER['SERVER_SOFTWARE'] . '</div>';
            echo '<div class="result-item"><strong>Sistema Operativo:</strong> ' . PHP_OS . '</div>';
            echo '<div class="result-item"><strong>Host:</strong> ' . $host . '</div>';
            echo '<div class="result-item"><strong>Base de Datos:</strong> ' . $dbname . '</div>';
            echo '<div class="result-item"><strong>Usuario:</strong> ' . $user . '</div>';
            echo '</div>';
            
            // ============================================
            // TEST 2: EXTENSION PDO
            // ============================================
            echo '<div class="test-section ' . (extension_loaded('PDO') ? 'success' : 'error') . '">';
            echo '<h2>🔌 2. Extensión PDO</h2>';
            
            if (extension_loaded('PDO')) {
                echo '<div class="alert alert-success">';
                echo '<strong>✅ PDO está instalado</strong><br>';
                echo 'La extensión PDO necesaria para la conexión está disponible.';
                echo '</div>';
                
                $drivers = PDO::getAvailableDrivers();
                echo '<div class="result-item"><strong>Drivers disponibles:</strong> ' . implode(', ', $drivers) . '</div>';
                
                if (in_array('mysql', $drivers)) {
                    echo '<div class="alert alert-success">✅ Driver MySQL disponible</div>';
                } else {
                    echo '<div class="alert alert-danger">❌ Driver MySQL NO disponible</div>';
                }
            } else {
                echo '<div class="alert alert-danger">';
                echo '<strong>❌ PDO NO está instalado</strong><br>';
                echo 'Contacta con soporte de Hostinger para habilitar PDO.';
                echo '</div>';
            }
            echo '</div>';
            
            // ============================================
            // TEST 3: CONEXION A LA BASE DE DATOS
            // ============================================
            $conexion_exitosa = false;
            $dbh = null;
            
            echo '<div class="test-section">';
            echo '<h2>🔗 3. Prueba de Conexión</h2>';
            
            try {
                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                $dbh = new PDO($dsn, $user, $password, $options);
                $conexion_exitosa = true;
                
                echo '<div class="alert alert-success">';
                echo '<strong>✅ CONEXIÓN EXITOSA</strong><br>';
                echo 'Se ha establecido la conexión con la base de datos correctamente.';
                echo '</div>';
                
            } catch (PDOException $e) {
                echo '<div class="alert alert-danger">';
                echo '<strong>❌ ERROR DE CONEXIÓN</strong><br><br>';
                echo '<strong>Mensaje de error:</strong><br>';
                echo $e->getMessage() . '<br><br>';
                
                // Diagnostico de errores comunes
                $error_msg = $e->getMessage();
                
                if (strpos($error_msg, 'Access denied') !== false) {
                    echo '<strong>🔍 Diagnóstico:</strong><br>';
                    echo '• Usuario o contraseña incorrectos<br>';
                    echo '• Verifica las credenciales en el panel de Hostinger<br>';
                    echo '• En Hostinger, las credenciales están en: Hosting → Bases de datos → phpMyAdmin<br>';
                    
                } elseif (strpos($error_msg, 'Unknown database') !== false) {
                    echo '<strong>🔍 Diagnóstico:</strong><br>';
                    echo '• La base de datos no existe<br>';
                    echo '• Verifica el nombre exacto en el panel de Hostinger<br>';
                    echo '• El nombre suele tener el formato: u123456789_nombredb<br>';
                    
                } elseif (strpos($error_msg, "Can't connect") !== false || strpos($error_msg, 'Connection refused') !== false) {
                    echo '<strong>🔍 Diagnóstico:</strong><br>';
                    echo '• No se puede conectar al servidor MySQL<br>';
                    echo '• Verifica que el host sea "localhost"<br>';
                    echo '• Contacta con soporte de Hostinger<br>';
                    
                } else {
                    echo '<strong>🔍 Diagnóstico:</strong><br>';
                    echo '• Error desconocido<br>';
                    echo '• Revisa todos los parámetros de conexión<br>';
                }
                
                echo '</div>';
            }
            
            echo '</div>';
            
            // ============================================
            // TEST 4: VERIFICACION DE TABLAS
            // ============================================
            if ($conexion_exitosa && $dbh) {
                echo '<div class="test-section success">';
                echo '<h2>📋 4. Verificación de Tablas</h2>';
                
                try {
                    // Listar todas las tablas
                    $stmt = $dbh->query("SHOW TABLES");
                    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    if (count($tablas) > 0) {
                        echo '<div class="alert alert-success">';
                        echo '<strong>✅ Se encontraron ' . count($tablas) . ' tabla(s)</strong>';
                        echo '</div>';
                        
                        echo '<table>';
                        echo '<thead><tr><th>Tabla</th><th>Registros</th><th>Estado</th></tr></thead>';
                        echo '<tbody>';
                        
                        $tablas_requeridas = ['ContratoDeCuotas', 'Cuotas'];
                        
                        foreach ($tablas as $tabla) {
                            try {
                                $count_stmt = $dbh->query("SELECT COUNT(*) as total FROM `$tabla`");
                                $count = $count_stmt->fetch()['total'];
                                $icono = in_array($tabla, $tablas_requeridas) ? '✅' : '📄';
                                echo "<tr><td>$icono $tabla</td><td>$count</td><td>OK</td></tr>";
                            } catch (PDOException $e) {
                                echo "<tr><td>⚠️ $tabla</td><td>-</td><td>Error al contar</td></tr>";
                            }
                        }
                        
                        echo '</tbody></table>';
                        
                        // Verificar tablas requeridas
                        $faltan_tablas = array_diff($tablas_requeridas, $tablas);
                        
                        if (count($faltan_tablas) > 0) {
                            echo '<div class="alert alert-danger">';
                            echo '<strong>❌ Faltan las siguientes tablas requeridas:</strong><br>';
                            echo implode(', ', $faltan_tablas);
                            echo '<br><br>Necesitas importar el archivo SQL con la estructura de la base de datos.';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-success">';
                            echo '<strong>✅ Todas las tablas requeridas están presentes</strong>';
                            echo '</div>';
                        }
                        
                    } else {
                        echo '<div class="alert alert-danger">';
                        echo '<strong>⚠️ La base de datos está vacía</strong><br>';
                        echo 'Necesitas importar el archivo SQL con la estructura de la base de datos.';
                        echo '</div>';
                    }
                    
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">';
                    echo 'Error al verificar tablas: ' . $e->getMessage();
                    echo '</div>';
                }
                
                echo '</div>';
                
                // ============================================
                // TEST 5: PRUEBA DE CONSULTA
                // ============================================
                if (in_array('Cuotas', $tablas)) {
                    echo '<div class="test-section success">';
                    echo '<h2>🎯 5. Prueba de Consulta</h2>';
                    
                    try {
                        $stmt = $dbh->query("SELECT * FROM Cuotas LIMIT 5");
                        $resultados = $stmt->fetchAll();
                        
                        if (count($resultados) > 0) {
                            echo '<div class="alert alert-success">';
                            echo '<strong>✅ Consulta ejecutada correctamente</strong><br>';
                            echo 'Se pueden leer datos de la tabla Cuotas.';
                            echo '</div>';
                            
                            echo '<table>';
                            echo '<thead><tr><th>Código</th><th>Descripción</th></tr></thead>';
                            echo '<tbody>';
                            foreach ($resultados as $row) {
                                echo '<tr><td>' . htmlspecialchars($row['Cod']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Descrip']) . '</td></tr>';
                            }
                            echo '</tbody></table>';
                        } else {
                            echo '<div class="alert alert-danger">';
                            echo '⚠️ La tabla Cuotas está vacía. Importa los datos del archivo SQL.';
                            echo '</div>';
                        }
                        
                    } catch (PDOException $e) {
                        echo '<div class="alert alert-danger">';
                        echo 'Error en consulta: ' . $e->getMessage();
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
            }
            
            // ============================================
            // CODIGO PARA datosConexionBase.php
            // ============================================
            if ($conexion_exitosa) {
                echo '<div class="test-section info">';
                echo '<h2>📝 6. Configuración para datosConexionBase.php</h2>';
                echo '<p>Copia esta configuración en tu archivo <strong>datosConexionBase.php</strong>:</p>';
                echo '<div class="code-block">';
                echo '&lt;?php<br>';
                echo '<span style="color:#75715e">// Configuración de conexión para Hostinger</span><br>';
                echo '$dbname = <span style="color:#e6db74">"' . $dbname . '"</span>;<br>';
                echo '$host = <span style="color:#e6db74">"' . $host . '"</span>;<br>';
                echo '$user = <span style="color:#e6db74">"' . $user . '"</span>;<br>';
                echo '$password = <span style="color:#e6db74">"' . $password . '"</span>;<br>';
                echo '?&gt;';
                echo '</div>';
                echo '</div>';
            }
            
            // Cerrar conexión
            if ($dbh) {
                $dbh = null;
            }
            ?>
            
            <!-- GUIA RAPIDA HOSTINGER -->
            <div class="test-section warning">
                <h2>📖 Guía Rápida de Hostinger</h2>
                <div style="line-height: 1.8;">
                    <strong>1. Obtener credenciales de la base de datos:</strong><br>
                    • Panel de Hostinger → Hosting → Bases de datos<br>
                    • Busca tu base de datos en la lista<br>
                    • Copia: Nombre de BD, Usuario, Contraseña<br>
                    • El Host siempre es: <strong>localhost</strong><br><br>
                    
                    <strong>2. Formato de nombres en Hostinger:</strong><br>
                    • Base de datos: <code>u123456789_nombre</code><br>
                    • Usuario: <code>u123456789_usuario</code><br>
                    • Contraseña: La que tú configuraste<br><br>
                    
                    <strong>3. Importar base de datos:</strong><br>
                    • Panel de Hostinger → Bases de datos → phpMyAdmin<br>
                    • Selecciona tu base de datos<br>
                    • Click en "Importar"<br>
                    • Selecciona tu archivo .sql<br>
                    • Click en "Continuar"<br><br>
                    
                    <strong>4. Problemas comunes:</strong><br>
                    • <strong>Access denied:</strong> Verifica usuario y contraseña<br>
                    • <strong>Unknown database:</strong> Verifica el nombre exacto de la BD<br>
                    • <strong>No tables:</strong> Importa el archivo SQL<br>
                    • <strong>500 Error:</strong> Revisa permisos de archivos (644 para PHP)<br>
                </div>
            </div>
            
            <div class="alert alert-danger" style="margin-top: 30px;">
                <strong>⚠️ IMPORTANTE - SEGURIDAD:</strong><br>
                Una vez que hayas verificado la conexión, ELIMINA este archivo (testConnection.php) de tu servidor.
                Este archivo contiene información sensible que no debe estar disponible públicamente.
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Sistema de Contratos en Cuotas</strong></p>
            <p>Test de Conexión v1.0 - Desarrollado para Hostinger</p>
        </div>
    </div>
</body>
</html>