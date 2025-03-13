<?php
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

// Obtener el tipo y el ID desde la URL
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

switch ($tipo) {
    case 'materia':
        $tabla = 'materias';
        $columna_id = 'materia_id';
        $titulo = 'Editar Materia';
        $permiso_columna = 'permiso_materias';
        break;
    case 'juego':
        $tabla = 'juegos';
        $columna_id = 'juego_id';
        $titulo = 'Editar Juego';
        $permiso_columna = 'permiso_juegos';
        break;
    case 'proyecto':
        $tabla = 'proyectos';
        $columna_id = 'proyecto_id';
        $titulo = 'Editar Proyecto';
        $permiso_columna = 'permiso_proyectos';
        break;
    default:
        echo "Tipo de entidad desconocido.";
        exit();
}

// Obtener el elemento actual
$sql = "SELECT nombre, descripcion FROM $tabla WHERE $columna_id = $id";
$result = $conn->query($sql);
$elemento = $result->fetch_assoc();

if (!$elemento) {
    echo "Elemento no encontrado.";
    exit();
}

// Obtener permisos actuales
$sql_permisos = "SELECT $permiso_columna FROM accesos WHERE usuario_id = $id";
$result_permisos = $conn->query($sql_permisos);
$permisos = ($result_permisos->num_rows > 0) ? explode(',', $result_permisos->fetch_assoc()[$permiso_columna]) : [];

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    // Obtener permisos desde el formulario
    $permisos_seleccionados = [];
    if (isset($_POST['crear'])) $permisos_seleccionados[] = 'crear';
    if (isset($_POST['leer'])) $permisos_seleccionados[] = 'leer';
    if (isset($_POST['actualizar'])) $permisos_seleccionados[] = 'actualizar';
    if (isset($_POST['eliminar'])) $permisos_seleccionados[] = 'eliminar';

    $permisos_actualizados = implode(',', $permisos_seleccionados);

    // Actualizar el elemento
    $sql_update = "UPDATE $tabla SET nombre = '$nombre', descripcion = '$descripcion' WHERE $columna_id = $id";
    $sql_update_permisos = "UPDATE accesos SET $permiso_columna = '$permisos_actualizados' WHERE usuario_id = $id";

    if ($conn->query($sql_update) === TRUE && $conn->query($sql_update_permisos) === TRUE) {
        // Redirigir a principal.php después de guardar cambios
        header("Location: principal.php");
        exit();
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2><?php echo $titulo; ?></h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($elemento['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción:</label>
                <textarea name="descripcion" class="form-control" rows="4" required><?php echo htmlspecialchars($elemento['descripcion']); ?></textarea>
            </div>

            <!-- Permisos CRUD -->
            <div class="mb-3">
                <label class="form-label">Permisos:</label><br>
                <div class="form-check">
                    <input type="checkbox" name="crear" class="form-check-input" id="crear" <?php echo in_array('crear', $permisos) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="crear">Crear</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="leer" class="form-check-input" id="leer" <?php echo in_array('leer', $permisos) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="leer">Leer</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="actualizar" class="form-check-input" id="actualizar" <?php echo in_array('actualizar', $permisos) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="actualizar">Actualizar</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="eliminar" class="form-check-input" id="eliminar" <?php echo in_array('eliminar', $permisos) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="eliminar">Eliminar</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="principal.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</body>
</html>
