<?php
require 'vendor/autoload.php';
use ClickSend\Api\SMSApi;
use ClickSend\Model\SmsMessage;
use ClickSend\Model\SmsMessageCollection;
use ClickSend\Configuration;
use GuzzleHttp\Client;

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerardo_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $celular = $_POST['celular'];

    // Verificar si el número de celular está registrado
    $sql = "SELECT * FROM usuarios WHERE celular = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $celular);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generar un código de verificación de 6 dígitos
        $codigo = rand(100000, 999999);

        // Guardar el código en la base de datos con tiempo de expiración
        $expira = date("Y-m-d H:i:s", strtotime("+10 minutes"));
        $sql = "UPDATE usuarios SET token = ?, token_expira = ? WHERE celular = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $codigo, $expira, $celular);
        $stmt->execute();

        // Configuración de ClickSend
        $config = Configuration::getDefaultConfiguration()
            ->setUsername('yarileth.jimenez.2404515@cobach.edu.mx')
            ->setPassword('00D57F98-1ECE-8CB5-1E2D-F34B27135F64'); 

        $client = new Client();
        $sms_api = new SMSApi($client, $config);

        // Crear el mensaje SMS
        $sms_message = new SmsMessage([
            'body' => "Tu código de verificación es: $codigo",
            'to' => "+52$celular",  // Asegúrate de usar el prefijo de tu país
            'source' => "php"
        ]);

        $sms_messages = new SmsMessageCollection(['messages' => [$sms_message]]);

        try {
            $response = $sms_api->smsSendPost($sms_messages);
            echo "Mensaje enviado correctamente. Revisa tu celular.";
            header("Location: verificar_codigo.php?celular=$celular");
            exit();
        } catch (Exception $e) {
            echo "Error al enviar el SMS: " . $e->getMessage();
        }

    } else {
        echo "Número de celular no registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña por SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow-lg">
                    <h3 class="text-center mb-3">Recuperar por SMS</h3>
                    <form action="recuperar_telefono_sms.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Número de Celular</label>
                            <input type="tel" name="celular" class="form-control" pattern="[0-9]{10}" required placeholder="Ej: 5512345678">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar Código</button>
                    </form>

                    <p class="mt-3 text-center">
                        <a href="login.php">Volver al inicio</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
