<?php
session_start();
require 'conexion.php';

if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];

    // Obtener el sesion_id actual del usuario
    $stmt = $conn->prepare("SELECT sesion_id FROM usuarios WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && $row['sesion_id'] !== session_id()) {
        // Si el usuario tiene una sesión diferente, cerramos esa sesión (borrando el token)
        $stmt = $conn->prepare("UPDATE usuarios SET sesion_id = NULL WHERE usuario_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
    }

    // Actualizar el sesion_id en la base de datos para la sesión actual
    $nuevo_sesion_id = session_id();
    $stmt = $conn->prepare("UPDATE usuarios SET sesion_id = ? WHERE usuario_id = ?");
    $stmt->bind_param("si", $nuevo_sesion_id, $usuario_id);
    $stmt->execute();

    // Redirigir a la página principal
    header("Location: principal.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
