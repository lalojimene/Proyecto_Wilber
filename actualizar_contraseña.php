<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verificar si el token sigue siendo válido
    $stmt = $conn->prepare("SELECT usuario_id FROM usuarios WHERE token = ? AND token_expira > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id);
        $stmt->fetch();

        // Actualizar la contraseña y eliminar el token
        $stmt = $conn->prepare("UPDATE usuarios SET password = ?, token = NULL, token_expira = NULL WHERE usuario_id = ?");
        $stmt->bind_param("si", $password, $id);
        $stmt->execute();

        echo "Contraseña restablecida con éxito. <a href='login.php'>Iniciar sesión</a>";
    } else {
        echo "Token inválido o expirado.";
    }
}
?>
