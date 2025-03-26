<?php
session_start();
require 'conexion.php';
require 'config.php'; // Configuración para conexión y envío de correos

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Incluye PHPMailer

// Suprimir los errores visibles
error_reporting(0);  // Suprime todos los errores
ini_set('display_errors', 0);  // Desactiva la visualización de errores en pantalla

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el correo existe en la base de datos
    $sql = "SELECT usuario_id, nombre, password, rol, sesion_id, mfa_activada FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Comprobar si ya tiene sesión activa en otro dispositivo
            if (isset($row['sesion_id']) && $row['sesion_id'] !== session_id()) {
                echo "<script>
                        if (confirm('Ya tienes una sesión activa en otro dispositivo. ¿Quieres cerrarla y mantener la sesión actual?')) {
                            window.location.href = 'cerrar_otras_sesiones.php?sesion_id=" . $row['sesion_id'] . "';
                        } else {
                            window.location.href = 'login.php';
                        }
                      </script>";
                exit();
            }

            // Verificar si MFA está activada
            if ($row['mfa_activada'] == 1) {
                // Generar token de MFA
                $token_mfa = bin2hex(random_bytes(50));
                $expira = date("Y-m-d H:i:s", strtotime("+15 minutes"));

                $stmt = $conn->prepare("UPDATE usuarios SET token = ?, token_expira = ?, sesion_id = ? WHERE email = ?");
                $stmt->bind_param("ssss", $token_mfa, $expira, session_id(), $email);
                $stmt->execute();

                // Definir las variables para los valores codificados
                $email_encoded = urlencode($email);
                $nombre_encoded = urlencode($row['nombre']);

                // Crear el enlace de verificación
                $enlace = "http://localhost/sesion_jwt_multifactor/verify_mfa.php?token=" . $token_mfa . "&email=" . $email_encoded . "&nombre=" . $nombre_encoded;

                // Crear el cuerpo del correo en formato HTML
                $cuerpo_correo = "
                <html>
                <head>
                    <title>Verificar identidad</title>
                </head>
                <body>
                    <p>Hola, " . $row['nombre'] . "</p>
                    <p>Haz clic en el siguiente enlace para verificar tu identidad:</p>
                    <p><a href='" . $enlace . "' target='_blank'>Verificar mi identidad</a></p>
                    <p>Si no puedes hacer clic en el enlace, copia y pega esta URL en tu navegador:</p>
                    <p><code>" . $enlace . "</code></p>
                </body>
                </html>
                ";

                // Configurar PHPMailer
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
                    $mail->setFrom('proyectowilber@alwaysdata.net', 'Sistema de autenticación');
                    $mail->addAddress($email);  // Destinatario

                    // Contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = 'Verificación de identidad';
                    $mail->Body = $cuerpo_correo;

                    $mail->send();
                    echo 'Correo de verificación enviado.';
                } catch (Exception $e) {
                    echo "Error al enviar el correo: {$mail->ErrorInfo}";
                }

                exit();
            } else {
                // Proceder con el inicio de sesión si MFA no está activado
                $_SESSION['usuario_id'] = $row['usuario_id'];
                $_SESSION['rol'] = $row['rol'];
                header("Location: principal.php");
            }
        } else {
            echo "<script>alert('Contraseña incorrecta');</script>";
        }
    } else {
        echo "<script>alert('Correo no registrado');</script>";
    }
}
?>

