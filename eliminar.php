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
        $tabla_accesos = 'accesos'; // Tabla que tiene la clave foránea
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
        echo "Tipo de entidad desconocido.";
        exit();
}

// Eliminar las filas relacionadas en la tabla 'accesos' (si es aplicable)
if ($tipo === 'materia') {
    $sql_accesos = "DELETE FROM $tabla_accesos WHERE $columna_id = $id";
    $conn->query($sql_accesos); // Eliminar las referencias en 'accesos'
}

// Eliminar el elemento
if ($id > 0) {
    $sql = "DELETE FROM $tabla WHERE $columna_id = $id";
    if ($conn->query($sql) === TRUE) {
        // Redirigir a principal.php después de eliminar el elemento
        header("Location: principal.php");
        exit();
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
} else {
    echo "ID no válido.";
}

$conn->close();
?>
