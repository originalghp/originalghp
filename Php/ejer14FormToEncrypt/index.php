<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario con required y autofocus</title>
</head>
<body>
    <h1>Formulario de Login</h1>
    
    <form action="procesar.php" method="POST">
        <p>
            <label>Usuario:</label><br>
            <input type="text" name="usuario" placeholder="Ingrese su usuario">
        </p>
        
        <p>
            <label>Clave:</label><br>
            <input type="text" name="clave" placeholder="Ingrese su clave" required autofocus>
        </p>
        
        <p>
            <button type="submit">Enviar</button>
        </p>
    </form>
    
    <hr>
    
    <h2>Explicación:</h2>
    <ul>
        <li><strong>autofocus:</strong> El cursor aparece automáticamente en el campo "Clave" al cargar la página</li>
        <li><strong>required:</strong> No puedes enviar el formulario si el campo "Clave" está vacío</li>
    </ul>
</body>
</html>