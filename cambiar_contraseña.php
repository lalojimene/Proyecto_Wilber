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
    $nueva_contraseña = $_POST['nueva_contraseña'];
    $repetir_contraseña = $_POST['repetir_contraseña'];

    // Validar que la nueva contraseña no esté vacía y que ambas contraseñas coincidan
    if (empty($nueva_contraseña) || empty($repetir_contraseña)) {
        echo "Las contraseñas no pueden estar vacías.";
    } elseif ($nueva_contraseña !== $repetir_contraseña) {
        echo "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
    } else {
        // Hash de la nueva contraseña
        $nueva_contraseña_hash = password_hash($nueva_contraseña, PASSWORD_BCRYPT);

        // Actualizar la contraseña en la base de datos
        $sql = "UPDATE usuarios SET password = ?, token = NULL, token_expira = NULL WHERE celular = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nueva_contraseña_hash, $celular);

        if ($stmt->execute()) {
            // Redirigir al login después de actualizar la contraseña
            header("Location: login.php");
            exit();
        } else {
            echo "Hubo un error al actualizar la contraseña. Inténtalo nuevamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .password-container {
            position: relative;
        }
        .password-container input[type="password"] {
            padding-right: 40px;
        }
        .password-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow-lg">
                    <h3 class="text-center mb-3">Cambiar Contraseña</h3>
                    <form action="cambiar_contraseña.php" method="POST">
                        <input type="hidden" name="celular" value="<?php echo $_GET['celular']; ?>">

                        <div class="mb-3 password-container">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="nueva_contraseña" class="form-control" id="nueva_contraseña" required>
                            <span class="toggle-password" onclick="togglePassword('nueva_contraseña')">👁️</span>
                        </div>

                        <div class="mb-3 password-container">
                            <label class="form-label">Repetir Contraseña</label>
                            <input type="password" name="repetir_contraseña" class="form-control" id="repetir_contraseña" required>
                            <span class="toggle-password" onclick="togglePassword('repetir_contraseña')">👁️</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Actualizar Contraseña</button>
                    </form>

                    <p class="mt-3 text-center">
                        <a href="login.php">Volver al inicio</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar la contraseña
        function togglePassword(inputId) {
            var input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>
</html>
