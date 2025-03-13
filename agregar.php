<?php
// Iniciar sesión
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerardo_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Obtener el ID de usuario desde la sesión
$usuario = $_SESSION['usuario'];
$sql_usuario = "SELECT usuario_id FROM usuarios WHERE nombre = '$usuario' LIMIT 1";
$result = $conn->query($sql_usuario);

if ($result->num_rows > 0) {
    $usuario_id = $result->fetch_assoc()['usuario_id'];
} else {
    die("Usuario no encontrado en la base de datos.");
}

// Definir el tipo de entidad
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';

    if (empty($nombre) || empty($descripcion)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        switch ($tipo) {
            case 'materia':
                $tabla = 'materias';
                $columna_id = 'materia_id';
                $columna_nombre = 'nombre';
                $columna_descripcion = 'descripcion';
                break;
            case 'juego':
                $tabla = 'juegos';
                $columna_id = 'juego_id';
                $columna_nombre = 'nombre';
                $columna_descripcion = 'descripcion';
                break;
            case 'proyecto':
                $tabla = 'proyectos';
                $columna_id = 'proyecto_id';
                $columna_nombre = 'nombre';
                $columna_descripcion = 'descripcion';
                break;
            default:
                $error = "Tipo de entidad desconocido.";
                break;
        }

        if (!isset($error)) {
            // Insertar el nuevo registro
            $sql = "INSERT INTO $tabla ($columna_nombre, $columna_descripcion) VALUES ('$nombre', '$descripcion')";
            if ($conn->query($sql) === TRUE) {
                $nuevo_id = $conn->insert_id;

                // Insertar el acceso en la tabla de accesos
                $sql_acceso = "INSERT INTO accesos (usuario_id, $columna_id) VALUES ($usuario_id, $nuevo_id)";
                if ($conn->query($sql_acceso) === TRUE) {
                    header("Location: principal.php");
                    exit();
                } else {
                    $error = "Error al agregar el acceso: " . $conn->error;
                }
            } else {
                $error = "Error al agregar: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar <?php echo ucfirst($tipo); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Agregar nuevo <?php echo ucfirst($tipo); ?></h2>
        
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

        <form action="agregar.php?tipo=<?php echo $tipo; ?>" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
            <a href="principal.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
