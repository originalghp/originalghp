<?php
session_start();
require_once 'db_config.php';

$user = isset($_POST['username']) ? $_POST['username'] : '';
$pass = isset($_POST['password']) ? $_POST['password'] : '';

$stmt = $conn->prepare('SELECT username, password, role FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $user);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    // Comparar contraseñas en texto plano (mejor usar hash en producción)
    if ($row['password'] === $pass) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        header('Location: index.php');
        exit;
    }
}
header('Location: login.php?error=1');
exit;
