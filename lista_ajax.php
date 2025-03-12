<?php
header('Content-Type: application/json');

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

// Obtener el usuario_id y tipo de la URL
$usuario_id = isset($_GET['usuario_id']) ? (int)$_GET['usuario_id'] : 0;
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if ($usuario_id === 0 || empty($tipo)) {
    echo json_encode(['error' => 'Información incompleta.']);
    exit();
}

// Definir tabla y columna según tipo
switch ($tipo) {
    case 'materia':
        $tabla = 'materias';
        $columna_id = 'materia_id';
        break;
    case 'juego':
        $tabla = 'juegos';
        $columna_id = 'juego_id';
        break;
    case 'proyecto':
        $tabla = 'proyectos';
        $columna_id = 'proyecto_id';
        break;
    default:
        echo json_encode(['error' => 'Tipo de entidad desconocido.']);
        exit();
}

// Consultar los elementos accesibles por el usuario
$sql = "SELECT $columna_id AS id, nombre FROM $tabla WHERE $columna_id IN (SELECT $columna_id FROM accesos WHERE usuario_id = $usuario_id)";
$result = $conn->query($sql);

$conn->close();

// Devolver los resultados en formato JSON
if ($result->num_rows > 0) {
    $elementos = [];
    while ($row = $result->fetch_assoc()) {
        $elementos[] = $row;
    }
    echo json_encode(['elementos' => $elementos]);
} else {
    echo json_encode(['error' => 'No se encontraron elementos.']);
}
?>
