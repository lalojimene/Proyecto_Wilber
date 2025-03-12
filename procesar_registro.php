<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $pregunta1 = $_POST['pregunta1'];
    $respuesta1 = $_POST['respuesta1'];
    $pregunta2 = $_POST['pregunta2'];
    $respuesta2 = $_POST['respuesta2'];

    $sql = "INSERT INTO usuarios (nombre, apellidos, email, celular, password, pregunta1, respuesta1, pregunta2, respuesta2) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $nombre, $apellidos, $email, $celular, $password, $pregunta1, $respuesta1, $pregunta2, $respuesta2);

    if ($stmt->execute()) {
        header("Location: login.php?registro=exitoso");
    } else {
        echo "Error al registrar: " . $stmt->error;
    }
}
?>
