<?php
session_start();
require 'conexion.php';
require 'config.php'; // Configuración para conexión y envío de correos

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Incluye PHPMailer

$clave_secreta = "mi_clave_secreta_super_segura";

if (isset($_GET['token']) && isset($_GET['email']) && isset($_GET['nombre'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];
    $nombre_usuario = $_GET['nombre'];

    // Verificar el token en la base de datos
    $sql = "SELECT usuario_id, nombre, rol, token_expira, sesion_id FROM usuarios WHERE token = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $expira = new DateTime($row['token_expira']);
        $ahora = new DateTime();

        if ($expira > $ahora) {
            // Comprobar si ya hay una sesión activa en otro dispositivo
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

            // Mostrar el formulario de verificación
            echo "<h2>Verificación de identidad</h2>";
            echo "<p>Nombre de usuario: <strong>$nombre_usuario</strong></p>";
            echo "<form action='verify_mfa.php' method='POST'>
                    <input type='hidden' name='token' value='$token'>
                    <input type='hidden' name='email' value='$email'>
                    <input type='hidden' name='nombre' value='$nombre_usuario'>
                    <button type='submit' name='verify' value='yes'>Sí, soy yo</button>
                    <button type='submit' name='verify' value='no'>No, no soy yo</button>
                  </form>";
        } else {
            echo "El enlace ha expirado.";
        }
    } else {
        echo "Token inválido.";
    }
}

if (isset($_POST['verify'])) {
    // Verificar si el usuario ha confirmado la verificación
    if ($_POST['verify'] == 'yes') {
        // Obtener el token y el email de la solicitud POST
        $token = $_POST['token'];
        $email = $_POST['email'];
        $nombre_usuario = $_POST['nombre'];

        // Obtener el usuario de la base de datos
        $sql = "SELECT usuario_id, rol, sesion_id FROM usuarios WHERE email = ? AND token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $usuario_id = $row['usuario_id'];
            $rol = $row['rol'];

            // Verificar si hay una sesión activa en otro dispositivo
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

            // Generar JWT manualmente
            $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
            $payload = base64_encode(json_encode(['usuario_id' => $usuario_id, 'nombre' => $nombre_usuario, 'rol' => $rol, 'exp' => time() + 3600]));
            $signature = hash_hmac('sha256', "$header.$payload", $clave_secreta, true);
            $signature = base64_encode($signature);
            $jwt = "$header.$payload.$signature";

            // Guardar en una cookie segura HTTP-only
            setcookie("jwt", $jwt, time() + 3600, "/", "", false, true);

            // Establecer la sesión y marcarla como activa
            $_SESSION['usuario'] = $nombre_usuario;
            $_SESSION['rol'] = $rol;
            $_SESSION['usuario_id'] = $usuario_id;
            $_SESSION['sesion_id'] = session_id(); // Guardar la ID de la sesión

            // Actualizar la base de datos con la nueva sesión activa
            $stmt = $conn->prepare("UPDATE usuarios SET sesion_id = ? WHERE usuario_id = ?");
            $stmt->bind_param("si", $_SESSION['sesion_id'], $usuario_id);
            $stmt->execute();

            // Redirigir a la página principal
            header("Location: principal.php");
            exit();
        } else {
            echo "Error al verificar el usuario.";
        }
    } else {
        // Si el usuario no es quien dice ser
        echo "Verificación fallida. Acceso denegado.";
    }
}
?>
