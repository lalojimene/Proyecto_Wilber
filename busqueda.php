<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

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
// Variables de búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Construcción de la consulta SQL con seguridad
$sql = "";
$params = [];
$types = "";

// Lógica para combinar ambos tipos de búsqueda en un solo campo
if ($categoria && $categoria !== "todas") {
    if ($categoria == "materias") {
        $sql = "SELECT m.materia_id AS id, 'materia' AS tipo, m.nombre, m.descripcion
                FROM materias m
                WHERE m.nombre LIKE ? OR m.descripcion LIKE ?";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types = "ss";
    } elseif ($categoria == "juegos") {
        $sql = "SELECT j.juego_id AS id, 'juego' AS tipo, j.nombre, j.descripcion
                FROM juegos j
                WHERE j.nombre LIKE ? OR j.descripcion LIKE ?";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types = "ss";
    } elseif ($categoria == "proyectos") {
        $sql = "SELECT p.proyecto_id AS id, 'proyecto' AS tipo, p.nombre, p.descripcion
                FROM proyectos p
                WHERE p.nombre LIKE ? OR p.descripcion LIKE ?";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types = "ss";
    }
} else {
    $sql = "(
                SELECT m.materia_id AS id, 'materia' AS tipo, m.nombre, m.descripcion
                FROM materias m
                WHERE m.nombre LIKE ? OR m.descripcion LIKE ?
            ) UNION (
                SELECT j.juego_id AS id, 'juego' AS tipo, j.nombre, j.descripcion
                FROM juegos j
                WHERE j.nombre LIKE ? OR j.descripcion LIKE ?
            ) UNION (
                SELECT p.proyecto_id AS id, 'proyecto' AS tipo, p.nombre, p.descripcion
                FROM proyectos p
                WHERE p.nombre LIKE ? OR p.descripcion LIKE ?
            )";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types = "ssssss";
}

// Preparar la consulta
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda Combinada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Búsqueda Combinada</h2>

    <!-- Formulario de Búsqueda -->
    <div class="card p-3 mb-4">
        <form method="GET" action="busqueda.php">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="categoria" class="form-label">Seleccione Categoría</label>
                    <select class="form-select" name="categoria" id="categoria">
                        <option value="" disabled selected>Elija una categoría</option>
                        <option value="materias" <?php echo $categoria == 'materias' ? 'selected' : ''; ?>>Materias</option>
                        <option value="juegos" <?php echo $categoria == 'juegos' ? 'selected' : ''; ?>>Juegos</option>
                        <option value="proyectos" <?php echo $categoria == 'proyectos' ? 'selected' : ''; ?>>Proyectos</option>
                        <option value="todas" <?php echo $categoria == 'todas' ? 'selected' : ''; ?>>Todas</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="search" class="form-label">Buscar por Nombre o Descripción</label>
                    <input type="text" class="form-control" name="search" id="search" placeholder="Ingrese nombre o descripción" value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <button type="submit" class="btn btn-primary mt-4">Buscar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Resultados -->
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['nombre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($row['descripcion'], 0, 100)) . '...'; ?></p>
                            <a href="descripcion.php?id=<?php echo $row['id']; ?>&tipo=<?php echo $row['tipo']; ?>" class="btn btn-primary">Ver más</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-danger">No se encontraron resultados.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
