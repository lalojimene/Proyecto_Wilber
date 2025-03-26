<?php
require 'config.php'; // Archivo con la conexión a la base de datos
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Incluir PHPMailer

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

        // Crear el enlace de recuperación
        $enlace = "http://localhost/sesion_jwt_multifactor/restablecer_contraseña.php?token=" . $token;
        $asunto = "Recuperar password";

        // Diseño del mensaje con botón y enlace en texto
        $mensaje = "
        <html>
        <head>
            <style>
                .boton {
                    display: inline-block;
                    padding: 10px 20px;
                    font-size: 16px;
                    color: #fff;
                    background-color: #007bff; /* Color azul */
                    text-decoration: none;
                    border-radius: 5px;
                }
                .boton:hover {
                    background-color: #0056b3; /* Azul más oscuro al pasar el mouse */
                }
            </style>
        </head>
        <body>
            <p>Haz clic en el botón para restablecer tu contraseña:</p>
            <p><a href='$enlace' class='boton'>Restablecer contraseña</a></p>
            <p>O copia y pega el siguiente enlace en tu navegador:</p>
            <p><a href='$enlace'>$enlace</a></p>
        </body>
        </html>";

        // Configuración de PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP de Alwaysdata
            $mail->isSMTP();
            $mail->Host = 'smtp-proyectowilber.alwaysdata.net';  // Servidor SMTP de Alwaysdata
            $mail->SMTPAuth = true;
            $mail->Username = 'proyectowilber@alwaysdata.net'; // Tu correo de Alwaysdata
            $mail->Password = 'tRfF4t6e4V@99';  // Tu contraseña de Alwaysdata
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Encriptación TLS
            $mail->Port = 587;  // Puerto para SMTP con TLS

            // Receptores
            $mail->setFrom('proyectowilber@alwaysdata.net', 'Soporte');
            $mail->addAddress($email); // Correo del destinatario

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;

            // Enviar el correo
            $mail->send();
            echo "Se ha enviado un enlace de recuperación a tu correo.";
        } catch (Exception $e) {
            echo "El correo no pudo ser enviado. Error de PHPMailer: {$mail->ErrorInfo}";
        }
    } else {
        echo "El correo no está registrado.";
    }
}
?>
