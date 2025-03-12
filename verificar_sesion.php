<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['pending_user'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['pending_user']['usuario_id'];
$nuevo_token = $_SESSION['pending_user']['nuevo_token'];

// Si el usuario elige cerrar las otras sesiones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sqlUpdate = "UPDATE usuarios SET sesion_token = ? WHERE usuario_id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("si", $nuevo_token, $usuario_id);
    $stmtUpdate->execute();

    // Establecer la sesión
    $_SESSION['usuario'] = $_SESSION['pending_user']['nombre'];
    $_SESSION['rol'] = $_SESSION['pending_user']['rol'];
    $_SESSION['sesion_token'] = $nuevo_token;
    unset($_SESSION['pending_user']);

    header("Location: principal.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Sesión</title>
</head>
<body>
    <h2>Ya tienes una sesión activa en otro dispositivo.</h2>
    <p>Si continúas, cerrarás la sesión anterior y mantendrás esta sesión activa.</p>
    <form method="POST">
        <button type="submit">Cerrar otras sesiones y continuar</button>
    </form>
</body>
</html>
