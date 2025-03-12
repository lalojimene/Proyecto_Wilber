<?php
session_start();
header('Content-Type: application/json');

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerardo_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Error de conexión"]);
    exit();
}
$conn->set_charset("utf8mb4");

// Parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%%";
$categoria = $_GET['categoria'] ?? 'todas';
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limit = 3;

// Consulta SQL
$sql = "SELECT id, tipo, nombre, descripcion FROM (
            SELECT materia_id AS id, 'materia' AS tipo, nombre, descripcion FROM materias
            UNION ALL
            SELECT juego_id AS id, 'juego' AS tipo, nombre, descripcion FROM juegos
            UNION ALL
            SELECT proyecto_id AS id, 'proyecto' AS tipo, nombre, descripcion FROM proyectos
        ) AS resultados
        WHERE (nombre LIKE ? OR descripcion LIKE ?)
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $search, $search, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>
