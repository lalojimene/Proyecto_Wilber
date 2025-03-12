<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Buscar usuario
    $sql = "SELECT usuario_id, nombre, password, rol, sesion_token FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $nuevo_token = bin2hex(random_bytes(32));

            // Si ya tiene una sesión activa, redirigir a verificar_sesion.php
            if (!empty($row['sesion_token'])) {
                $_SESSION['pending_user'] = [
                    'usuario_id' => $row['usuario_id'],
                    'nombre' => $row['nombre'],
                    'rol' => $row['rol'],
                    'nuevo_token' => $nuevo_token
                ];
                header("Location: verificar_sesion.php");
                exit();
            }

            // Iniciar sesión normalmente
            $_SESSION['usuario'] = $row['nombre'];
            $_SESSION['rol'] = $row['rol'];
            $_SESSION['sesion_token'] = $nuevo_token;

            // Guardar el nuevo token en la base de datos
            $sqlUpdate = "UPDATE usuarios SET sesion_token = ? WHERE usuario_id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("si", $nuevo_token, $row['usuario_id']);
            $stmtUpdate->execute();

            header("Location: principal.php");
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location='login.php';</script>";
    }
}
?>
