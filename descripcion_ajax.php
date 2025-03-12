<?php
// Conexión a la base de datos
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerardo_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Obtener el id y tipo de la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if ($id === 0 || empty($tipo)) {
    echo json_encode(['error' => 'Información incompleta.']);
    exit();
}

// Definir la tabla y las columnas según el tipo
switch ($tipo) {
    case 'materia':
        $sql = "SELECT m.descripcion, m.nombre AS item_nombre
                FROM materias m
                WHERE m.materia_id = $id LIMIT 1";
        break;
    case 'juego':
        $sql = "SELECT j.descripcion, j.nombre AS item_nombre
                FROM juegos j
                WHERE j.juego_id = $id LIMIT 1";
        break;
    case 'proyecto':
        $sql = "SELECT p.descripcion, p.nombre AS item_nombre
                FROM proyectos p
                WHERE p.proyecto_id = $id LIMIT 1";
        break;
    default:
        echo json_encode(['error' => 'Tipo de entidad desconocido.']);
        exit();
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $descripcion = $result->fetch_assoc();
    echo json_encode(['descripcion' => nl2br(htmlspecialchars($descripcion['descripcion']))]);
} else {
    echo json_encode(['error' => 'No se encontró la descripción.']);
}

$conn->close();
?>
