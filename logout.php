<?php
session_start();
require 'conexion.php';

if (isset($_SESSION['usuario'])) {
    $sql = "UPDATE usuarios SET sesion_token = NULL WHERE nombre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['usuario']);
    $stmt->execute();
}

// Destruir sesión y redirigir al login
session_destroy();
header("Location: login.php");
exit();
?>
