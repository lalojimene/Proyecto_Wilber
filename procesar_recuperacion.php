<?php
require 'conexion.php'; // Asegúrate de que tienes la conexión establecida a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Comprobamos si los campos están presentes en el formulario
    if (isset($_POST['correo']) && isset($_POST['nueva_contraseña']) && isset($_POST['confirmar_contraseña'])) {
        $correo = $_POST['correo']; // Correo ingresado por el usuario
        $nueva_contraseña = $_POST['nueva_contraseña']; // Nueva contraseña
        $confirmar_contraseña = $_POST['confirmar_contraseña']; // Confirmación de la nueva contraseña

        // Verificamos si las contraseñas coinciden
        if ($nueva_contraseña !== $confirmar_contraseña) {
            echo "Las contraseñas no coinciden. Por favor, intenta nuevamente.";
            exit;
        }

        // Encriptamos la nueva contraseña
        $password_hash = password_hash($nueva_contraseña, PASSWORD_BCRYPT);

        // Verificamos si el correo existe en la base de datos
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Si el correo existe, actualizamos la contraseña
            $sql_update = "UPDATE usuarios SET password = ? WHERE email = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ss", $password_hash, $correo);

            if ($stmt_update->execute()) {
                // Redirigir al login.php después de actualizar la contraseña
                header("Location: login.php");
                exit(); // Asegúrate de terminar el script después de la redirección
            } else {
                echo "Hubo un error al actualizar la contraseña. Intenta nuevamente.";
            }
        } else {
            echo "No se encontró un usuario con ese correo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Función para mostrar/ocultar la contraseña
        function togglePassword(id) {
            var input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow-lg">
                    <h3 class="text-center mb-3">Actualizar Contraseña</h3>

                    <form action="procesar_recuperacion.php" method="POST">
                        <!-- Correo -->
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>

                        <!-- Nueva Contraseña -->
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <div class="input-group">
                                <input type="password" id="nueva_contraseña" name="nueva_contraseña" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('nueva_contraseña')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-3">
                            <label class="form-label">Repetir Contraseña</label>
                            <div class="input-group">
                                <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirmar_contraseña')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
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

    <!-- Agregar iconos de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>
