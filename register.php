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

// Consulta para las preguntas de la primera selección (ID 1 al 4)
$sql1 = "SELECT pregunta_id, pregunta FROM preguntas_secreta WHERE pregunta_id BETWEEN 1 AND 4";
$result1 = $conn->query($sql1);

$preguntas1 = [];
if ($result1 === FALSE) {
    die("Error en la consulta de preguntas 1: " . $conn->error);
} elseif ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $preguntas1[] = $row;
    }
}

// Consulta para las preguntas de la segunda selección (ID 5 al 8)
$sql2 = "SELECT pregunta_id, pregunta FROM preguntas_secreta WHERE pregunta_id BETWEEN 5 AND 8";
$result2 = $conn->query($sql2);

$preguntas2 = [];
if ($result2 === FALSE) {
    die("Error en la consulta de preguntas 2: " . $conn->error);
} elseif ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $preguntas2[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card p-4 shadow-lg">
                    <h3 class="text-center mb-3">Registro</h3>

                    <form action="procesar_registro.php" method="POST" onsubmit="return validarContraseñas()">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" name="apellidos" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Correo</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Número de Celular</label>
                                    <input type="tel" name="celular" class="form-control" pattern="[0-9]{10}" required placeholder="Ej: 5512345678">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3 position-relative">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    <i class="bi bi-eye-slash toggle-password" onclick="verPassword('password', this)" style="position: absolute; right: 10px; top: 38px; cursor: pointer;"></i>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3 position-relative">
                                    <label class="form-label">Repetir Contraseña</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                    <i class="bi bi-eye-slash toggle-password" onclick="verPassword('confirm_password', this)" style="position: absolute; right: 10px; top: 38px; cursor: pointer;"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Pregunta secreta 1 -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Pregunta Secreta 1</label>
                                    <select name="pregunta1" class="form-control" required>
                                        <option value="" selected disabled>Selecciona una pregunta</option>
                                        <?php
                                        foreach ($preguntas1 as $pregunta) {
                                            echo "<option value='" . $pregunta['pregunta_id'] . "'>" . $pregunta['pregunta'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Respuesta</label>
                                    <input type="text" name="respuesta1" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Pregunta secreta 2 -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Pregunta Secreta 2</label>
                                    <select name="pregunta2" class="form-control" required>
                                        <option value="" selected disabled>Selecciona una pregunta</option>
                                        <?php
                                        foreach ($preguntas2 as $pregunta) {
                                            echo "<option value='" . $pregunta['pregunta_id'] . "'>" . $pregunta['pregunta'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Respuesta</label>
                                    <input type="text" name="respuesta2" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Términos y condiciones -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terminos" required>
                                    <label class="form-check-label" for="terminos">
                                        Acepto los <a href="terminos.php" target="_blank">términos y condiciones</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                            </div>
                            <div class="col-md-6">
                                <a href="principal.php" class="btn btn-secondary w-100">Regresar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
