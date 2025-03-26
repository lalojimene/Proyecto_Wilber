<?php
session_start();

// Verificar si la sesión de usuario está activa
if (!isset($_SESSION['usuario_id'])) {
    // Si no está activa, redirigir al login
    header("Location: login.php?expired=1");
    exit();
}

// Obtener la información del usuario desde la sesión
$usuario_id = $_SESSION['usuario_id'];

// Verificar si la variable 'nombre' está definida en la sesión
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario no identificado'; // Valor predeterminado si no está definida

$usuarioRol = $_SESSION['rol'] ?? 'Rol no definido'; // También con valor predeterminado

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

// Consulta para obtener el nombre del usuario
$sqlUsuario = "SELECT usuario_id, nombre, rol, sesion_id FROM usuarios WHERE usuario_id = ?";
$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $usuario_id);
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();
$usuarioActual = $resultUsuario->fetch_assoc();

// Asignar nombre a la sesión
$_SESSION['nombre'] = $usuarioActual['nombre'];


// Verificar si la sesión corresponde con la registrada en la base de datos
if ($usuarioActual['sesion_id'] !== session_id()) {
    // Si las sesiones no coinciden, cerrar sesión y mostrar mensaje
    session_destroy();
    echo "<script>
            alert('Tu sesión fue cerrada desde otro dispositivo.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

// Obtener los usuarios con rol 'usuario'
$sqlUsuarios = "SELECT usuario_id, nombre FROM usuarios WHERE rol = 'usuario'";
$resultUsuarios = $conn->query($sqlUsuarios);

// Obtener las materias, juegos y proyectos de cada usuario
$usuariosAccesos = [];
while ($usuario = $resultUsuarios->fetch_assoc()) {
    $sqlAccesos = "
        SELECT m.materia_id, m.nombre AS materia, 
               j.juego_id, j.nombre AS juego, 
               p.proyecto_id, p.nombre AS proyecto
        FROM accesos a
        LEFT JOIN materias m ON a.materia_id = m.materia_id
        LEFT JOIN juegos j ON a.juego_id = j.juego_id
        LEFT JOIN proyectos p ON a.proyecto_id = p.proyecto_id
        WHERE a.usuario_id = ?";
    $stmtAccesos = $conn->prepare($sqlAccesos);
    $stmtAccesos->bind_param("i", $usuario['usuario_id']);
    $stmtAccesos->execute();
    $resultAccesos = $stmtAccesos->get_result();

    $accesos = [];
    while ($row = $resultAccesos->fetch_assoc()) {
        $accesos[] = $row;
    }

    $usuariosAccesos[$usuario['usuario_id']] = [
        'nombre' => $usuario['nombre'],
        'accesos' => $accesos
    ];
}

// Aquí cierras la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>

    <script>
        // 🔥 Cerrar sesión automáticamente después de 3 minutos (180,000 ms)
        setTimeout(function () {
            alert("Tu sesión ha expirado. Serás redirigido al inicio de sesión.");
            window.location.href = "logout.php";
        }, 180000); // 3 minutos en milisegundos
    </script>
</head>
<body>
    
</body>
</html>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Sistema Web</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="principal.php">Sistema Web</a>
        
        <!-- Botón de menú lateral (Asegurar que funciona) -->
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="navbar-nav ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i> <?php echo $nombre_usuario; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="perfil.php">Configuración</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Salir</a>
                </div>
            </li>
        </ul>
    </nav>




    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <!-- Contenido del menú -->
                        <?php foreach ($usuariosAccesos as $id => $usuario): ?>
                            <?php 
                                $bloqueado = ($usuarioRol == 'usuario' && $id != $usuarioActual['usuario_id']); 
                                $icono = $bloqueado ? '<i class="fas fa-lock"></i>' : '<i class="fas fa-user"></i>';
                                $dataToggle = $bloqueado ? '' : 'data-toggle="collapse"';
                                $dataTarget = $bloqueado ? '' : 'data-target="#usuario' . $id . '"';
                            ?>
                            <a class="nav-link" href="#" <?php echo $dataToggle . ' ' . $dataTarget; ?> aria-expanded="false" aria-controls="usuario<?php echo $id; ?>">
                                <div class="sb-nav-link-icon"><?php echo $icono; ?></div>
                                <?php echo $usuario['nombre']; ?>
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="usuario<?php echo $id; ?>">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <!-- Materias -->
                                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#materias<?php echo $id; ?>" aria-expanded="false" aria-controls="materias<?php echo $id; ?>">
                                        Materias
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="materias<?php echo $id; ?>">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <?php foreach ($usuario['accesos'] as $acceso): ?>
                                                <?php if (!empty($acceso['materia'])): ?>
                                                    <a class="nav-link <?php echo $bloqueado ? 'text-muted' : ''; ?>" href="<?php echo !$bloqueado ? 'descripcion.php?id=' . $acceso['materia_id'] . '&tipo=materia' : '#'; ?>">
                                                        <?php echo $acceso['materia']; ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </nav>
                                    </div>

                                    <!-- Juegos -->
                                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#juegos<?php echo $id; ?>" aria-expanded="false" aria-controls="juegos<?php echo $id; ?>">
                                        Juegos
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="juegos<?php echo $id; ?>">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <?php foreach ($usuario['accesos'] as $acceso): ?>
                                                <?php if (!empty($acceso['juego'])): ?>
                                                    <a class="nav-link <?php echo $bloqueado ? 'text-muted' : ''; ?>" href="<?php echo !$bloqueado ? 'descripcion.php?id=' . $acceso['juego_id'] . '&tipo=juego' : '#'; ?>">
                                                        <?php echo $acceso['juego']; ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </nav>
                                    </div>

                                    <!-- Proyectos -->
                                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#proyectos<?php echo $id; ?>" aria-expanded="false" aria-controls="proyectos<?php echo $id; ?>">
                                        Proyectos
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="proyectos<?php echo $id; ?>">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <?php foreach ($usuario['accesos'] as $acceso): ?>
                                                <?php if (!empty($acceso['proyecto'])): ?>
                                                    <a class="nav-link <?php echo $bloqueado ? 'text-muted' : ''; ?>" href="<?php echo !$bloqueado ? 'descripcion.php?id=' . $acceso['proyecto_id'] . '&tipo=proyecto' : '#'; ?>">
                                                        <?php echo $acceso['proyecto']; ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </nav>
                                    </div>
                                </nav>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </nav>
        </div>
        
        <!-- Contenido principal -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                <main><?php
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

// Obtener el id y tipo de la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

// Verificar que los parámetros de la URL sean válidos
if ($id === 0 || empty($tipo)) {
    echo "Información incompleta.";
    exit();
}

// Verificar si el usuario tiene acceso a la entidad solicitada
$usuario_id = $_SESSION['usuario_id'];  // Suponiendo que el ID del usuario está guardado en la sesión

// Definir la tabla y las columnas según el tipo
switch ($tipo) {
    case 'materia':
        $sql = "SELECT m.descripcion, m.nombre AS item_nombre, u.nombre AS usuario_nombre, u.usuario_id
                FROM materias m
                JOIN accesos a ON m.materia_id = a.materia_id
                JOIN usuarios u ON a.usuario_id = u.usuario_id
                WHERE m.materia_id = $id AND u.usuario_id = $usuario_id LIMIT 1";
        $titulo = 'Materia';
        break;
    case 'juego':
        $sql = "SELECT j.descripcion, j.nombre AS item_nombre, u.nombre AS usuario_nombre, u.usuario_id
                FROM juegos j
                JOIN accesos a ON j.juego_id = a.juego_id
                JOIN usuarios u ON a.usuario_id = u.usuario_id
                WHERE j.juego_id = $id AND u.usuario_id = $usuario_id LIMIT 1";
        $titulo = 'Juego';
        break;
    case 'proyecto':
        $sql = "SELECT p.descripcion, p.nombre AS item_nombre, u.nombre AS usuario_nombre, u.usuario_id
                FROM proyectos p
                JOIN accesos a ON p.proyecto_id = a.proyecto_id
                JOIN usuarios u ON a.usuario_id = u.usuario_id
                WHERE p.proyecto_id = $id AND u.usuario_id = $usuario_id LIMIT 1";
        $titulo = 'Proyecto';
        break;
    default:
        echo "Tipo de entidad desconocido.";
        exit();
}

// Ejecutar la consulta para verificar si el usuario tiene acceso
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $descripcion = $result->fetch_assoc();
    $nombre_usuario = $descripcion['usuario_nombre'];
    $usuario_id = $descripcion['usuario_id'];
} else {
    echo "No tienes acceso a esta entidad o la entidad no existe.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descripción de <?php echo htmlspecialchars($descripcion['item_nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
            margin-top: 30px;
        }
        .breadcrumb {
            background-color: #e9ecef;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .breadcrumb-item a {
            color: #007bff;
            text-decoration: none;
        }
        .breadcrumb-item.active {
            color: #6c757d;
        }
        h2 {
            color: #343a40;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.5em;
            font-weight: bold;
        }
        .card-text {
            font-size: 1.1em;
            color: #495057;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="principal.php">Inicio</a></li>
                        <li class="breadcrumb-item">
                            <a href="menu_usuario.php?usuario_id=<?php echo $usuario_id; ?>">
                                <?php echo htmlspecialchars($nombre_usuario); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="lista_elementos.php?usuario_id=<?php echo $usuario_id; ?>&tipo=<?php echo $tipo; ?>">
                                <?php echo $titulo; ?>s
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($descripcion['item_nombre']); ?></li>
                    </ol>
                </nav>

                <h2>Descripción de <?php echo htmlspecialchars($descripcion['item_nombre']); ?></h2>
                <div id="descripcion-container" class="card">
                    <div class="card-body">
                        <p id="descripcion-texto"><?php echo nl2br(htmlspecialchars($descripcion['descripcion'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>


</main>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        // Script para mostrar y ocultar el sidebar
        document.addEventListener("DOMContentLoaded", function () {
            var sidebarToggle = document.getElementById("sidebarToggle");
            if (sidebarToggle) {
                sidebarToggle.addEventListener("click", function (event) {
                    event.preventDefault();
                    document.body.classList.toggle("sb-sidenav-toggled");
                });
            }
        });
    </script>

</body>
</html>
