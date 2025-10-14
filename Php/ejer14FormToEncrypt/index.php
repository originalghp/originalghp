<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form to Encrypt</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            line-height: 1.6;
        }
        
        main {
            max-width: 900px;
            margin: 0 auto;
        }
        
        h1 {
            font-size: 1.2em;
            margin-bottom: 15px;
            font-weight: normal;
        }
        
        form {
            margin-bottom: 30px;
        }
        
        input[type="text"] {
            padding: 5px 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-right: 10px;
        }
        
        button {
            padding: 5px 15px;
            background-color: #f0f0f0;
            border: 1px solid #999;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }
        
        button:hover {
            background-color: #e0e0e0;
        }
        
        .resultado {
            margin: 20px 0;
        }
        
        .resultado p {
            margin: 8px 0;
        }
        
        .hash-valor {
            color: blue;
            font-family: monospace;
            word-break: break-all;
        }
        
        .aclaracion {
            color: #666;
            font-size: 0.95em;
        }
    </style>
</head>
<body>
    <main>
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
            ?>
            <section class="resultado">
                <p><strong>Clave:</strong> <?php echo htmlspecialchars($claveOriginal); ?></p>
                <p>
                    <strong>Clave encriptada en md5</strong> 
                    <span class="aclaracion">(128 bits o 16 octetos o 16 pares hexadecimales):</span>
                </p>
                <p class="hash-valor"><?php echo $claveMd5; ?></p>
            </section>
            
            <section class="resultado">
                <p><strong>Clave:</strong> <?php echo htmlspecialchars($claveOriginal); ?></p>
                <p>
                    <strong>Clave encriptada en sha1</strong> 
                    <span class="aclaracion">(160 bits o 20 octetos o 20 pares hexadecimales):</span>
                </p>
                <p class="hash-valor"><?php echo $claveSha1; ?></p>
            </section>
            
            <a href="index.php">
                <button type="button">Encriptar otra clave</button>
            </a>
            <?php
        } else {
            // Mostrar formulario
            ?>
            <h1>Ingrese la clave a encriptar:</h1>
            <form method="post" action="index.php">
                <input type="text" name="clave" placeholder="Ingrese su clave" required autofocus>
                <button type="submit">Obtener encriptación</button>
            </form>
            <?php
        }
        ?>
    </main>
</body>
</html>