<?php
require 'config.php'; // Archivo con la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verificar si el correo existe en la base de datos
    $stmt = $conn->prepare("SELECT usuario_id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // Crear un token único
        $token = bin2hex(random_bytes(50));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Guardar el token en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET token = ?, token_expira = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expira, $email);
        $stmt->execute();

        // Enviar el correo con el enlace de recuperación
        $enlace = "http://localhost/sistemabd/restablecer_contraseña.php?token=" . $token;
        $asunto = "Recuperación de contraseña";
        $mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña: $enlace";
        $cabeceras = "From: uts@gerardo.com\r\nContent-Type: text/html;";

        mail($email, $asunto, $mensaje, $cabeceras);

        echo "Se ha enviado un enlace de recuperación a tu correo.";
    } else {
        echo "El correo no está registrado.";
    }
}
?>
