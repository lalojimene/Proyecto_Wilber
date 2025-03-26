<?php
session_start();
require 'conexion.php';

if (isset($_GET['sesion_id'])) {
    $sesion_id = $_GET['sesion_id'];

    // Desconectar la sesión en la base de datos
    $sql = "UPDATE usuarios SET sesion_id = NULL WHERE sesion_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $sesion_id);
    $stmt->execute();

    // Regresar a la página de login después de cerrar la sesión
    header("Location: login.php");
    exit();
}
?>
