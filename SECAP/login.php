<?php
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Ya logueado</title><style>body{font-family:Arial,sans-serif;background:#eef2ff;}.container{max-width:400px;margin:80px auto;background:#fff;padding:2rem;border-radius:1rem;box-shadow:0 8px 24px rgba(0,0,0,0.08);}h2{text-align:center;color:#4f46e5;margin-bottom:1.5rem;}.logout{text-align:center;margin-top:2rem;}.logout a{color:#dc2626;text-decoration:none;font-weight:bold;}</style></head><body><div class="container"><h2>Ya has iniciado sesión</h2><p>Usuario: <b>' . htmlspecialchars($_SESSION['username']) . '</b></p><div class="logout"><a href="logout.php">Cerrar sesión</a></div></div></body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login SECAP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef2ff; }
        .login-container { max-width: 400px; margin: 80px auto; background: #fff; padding: 2rem; border-radius: 1rem; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        h2 { text-align: center; color: #4f46e5; margin-bottom: 1.5rem; }
        .input-group { margin-bottom: 1.2rem; }
        label { display: block; margin-bottom: 0.5rem; color: #1f2937; font-weight: 600; }
        input[type="text"], input[type="password"] { width: 100%; padding: 0.7rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 1.1rem; }
        button { width: 100%; padding: 0.8rem; background: #4f46e5; color: #fff; border: none; border-radius: 0.5rem; font-size: 1.2rem; font-weight: 600; cursor: pointer; }
        button:hover { background: #4338ca; }
        .error { color: #dc2626; text-align: center; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Acceso SECAP</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error">Usuario o contraseña incorrectos</div>
        <?php endif; ?>
        <form method="post" action="login_process.php">
            <div class="input-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
