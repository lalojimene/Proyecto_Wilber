<?php
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

// Si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $celular = $_POST['celular'];
    $codigo_ingresado = $_POST['codigo'];

    // Verificar el código en la base de datos
    $sql = "SELECT * FROM usuarios WHERE celular = ? AND token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $celular, $codigo_ingresado);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $expira = strtotime($usuario['token_expira']);
        $ahora = time();

        // Verificar si el código ha expirado
        if ($expira > $ahora) {
            // Código válido y no expirado, redirigir a cambiar contraseña
            header("Location: cambiar_contraseña.php?celular=$celular");
            exit();
        } else {
            $mensaje_error = "El código ha expirado. Solicita uno nuevo.";
        }
    } else {
        $mensaje_error = "El código ingresado es incorrecto o el celular no está registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error-message {
            position: fixed;
            top: 10px; /* Ajustado a la parte superior */
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(255, 0, 0, 0.8);
            color: white;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            display: none;
            z-index: 9999; /* Asegura que esté sobre otros elementos */
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

    <?php if (isset($mensaje_error)) : ?>
        <div class="error-message" id="error-message">
            <?php echo $mensaje_error; ?>
        </div>
        <script>
            // Mostrar el mensaje de error y ocultarlo después de 5 segundos
            document.getElementById("error-message").style.display = "block";
            setTimeout(function() {
                document.getElementById("error-message").style.display = "none";
            }, 5000);
        </script>
    <?php endif; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow-lg">
                    <h3 class="text-center mb-3">Verificar Código</h3>
                    <form action="verificar_codigo.php" method="POST">
                        <input type="hidden" name="celular" value="<?php echo $_GET['celular']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Código de Verificación</label>
                            <input type="text" name="codigo" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Verificar</button>
                    </form>

                    <p class="mt-3 text-center">
                        <a href="recuperar_telefono_sms.php">¿No recibiste el código? Solicita uno nuevo</a>
                    </p>
                    <p class="mt-3 text-center">
                        <a href="login.php">Volver al inicio</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
