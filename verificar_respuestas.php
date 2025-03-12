<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: recuperar_pregunta.php");
    exit();
}

$pregunta1 = $_SESSION['pregunta1'];
$pregunta2 = $_SESSION['pregunta2'];

$sql = "SELECT pregunta FROM preguntas_secreta WHERE pregunta_id IN (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $pregunta1, $pregunta2);
$stmt->execute();
$result = $stmt->get_result();

$preguntas = [];
while ($row = $result->fetch_assoc()) {
    $preguntas[] = $row['pregunta'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respuesta1 = $_POST['respuesta1'];
    $respuesta2 = $_POST['respuesta2'];
    $usuario_id = $_SESSION['usuario_id'];

    $sql = "SELECT * FROM usuarios WHERE usuario_id = ? AND respuesta1 = ? AND respuesta2 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $usuario_id, $respuesta1, $respuesta2);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: nueva_contraseña.php");
        exit();
    } else {
        $error = "Respuestas incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Respuestas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4 shadow-lg">
                    <h3 class="text-center mb-3">Verificar Respuestas</h3>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label"><?php echo $preguntas[0]; ?></label>
                            <input type="text" name="respuesta1" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?php echo $preguntas[1]; ?></label>
                            <input type="text" name="respuesta2" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Verificar</button>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
