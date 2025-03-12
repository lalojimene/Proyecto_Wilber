<?php
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
// Obtener los datos del usuario actual
$sqlUsuario = "SELECT usuario_id, nombre, rol FROM usuarios WHERE nombre = '" . $_SESSION['usuario'] . "' LIMIT 1";
$resultUsuario = $conn->query($sqlUsuario);
$usuarioActual = $resultUsuario->fetch_assoc();
$usuarioRol = $usuarioActual['rol'];

// Obtener todos los usuarios con rol 'usuario'
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
        WHERE a.usuario_id = " . $usuario['usuario_id'];
    $resultAccesos = $conn->query($sqlAccesos);

    $accesos = [];
    while ($row = $resultAccesos->fetch_assoc()) {
        $accesos[] = $row;
    }

    $usuariosAccesos[$usuario['usuario_id']] = [
        'nombre' => $usuario['nombre'],
        'accesos' => $accesos
    ];
}

$conn->close();
?>

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
                    <i class="fas fa-user fa-fw"></i> <?php echo $_SESSION['usuario']; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">Configuración</a>
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
                <?php
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
// Obtener el usuario_id de la URL
$usuario_id = isset($_GET['usuario_id']) ? (int)$_GET['usuario_id'] : 0;

if ($usuario_id === 0) {
    echo "Información incompleta.";
    exit();
}

// Consultar nombre del usuario
$sql_usuario = "SELECT nombre FROM usuarios WHERE usuario_id = $usuario_id LIMIT 1";
$result_usuario = $conn->query($sql_usuario);
$nombre_usuario = ($result_usuario->num_rows > 0) ? $result_usuario->fetch_assoc()['nombre'] : "Usuario desconocido";

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de <?php echo htmlspecialchars($nombre_usuario); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin-top: 40px;
        }
        .breadcrumb {
            background-color: #f8f9fa;
            padding: 15px 25px;
            border-radius: 5px;
        }
        .breadcrumb-item a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }
        .breadcrumb-item.active {
            color: #6c757d;
        }
        .carousel-card {
            background: linear-gradient(135deg, rgba(63,94,251,1) 0%, rgba(252,70,107,1) 100%);
            border-radius: 12px;
            color: white;
            padding: 30px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .carousel-card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 15px 35px rgba(0, 0, 0, 0.2);
        }
        .carousel-card h5 {
            font-size: 1.8em;
            font-weight: bold;
        }
        .carousel-card p {
            font-size: 1.2em;
            font-weight: 300;
        }
        .carousel-card a {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .carousel-card a:hover {
            background-color: #0056b3;
        }
        .carousel-inner {
            padding: 40px;
        }
        .carousel-control-prev, .carousel-control-next {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 50%;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            filter: invert(100%);
        }
        .carousel-caption {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            font-size: 3.5em;
            font-weight: 700;
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
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($nombre_usuario); ?></li>
                    </ol>
                </nav>

                <!-- Carrusel con la frase "Menú de <?php echo htmlspecialchars($nombre_usuario); ?>" -->
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Primera tarjeta del carrusel -->
                        <div class="carousel-item active">
                            <div class="carousel-card">
                                <div class="carousel-caption">
                                    🧑‍💻 Menú de <?php echo htmlspecialchars($nombre_usuario); ?> 
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta de Materias -->
                        <div class="carousel-item">
                            <div class="card carousel-card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">📚 Materias</h5>
                                    <p class="card-text">Accede a las materias disponibles y mejora tus conocimientos. 🧠</p>
                                    <a href="lista_elementos.php?usuario_id=<?php echo $usuario_id; ?>&tipo=materia">Ver Materias</a>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta de Juegos -->
                        <div class="carousel-item">
                            <div class="card carousel-card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">🎮 Juegos</h5>
                                    <p class="card-text">Juega y aprende con nuestros juegos interactivos. 🕹️</p>
                                    <a href="lista_elementos.php?usuario_id=<?php echo $usuario_id; ?>&tipo=juego">Ver Juegos</a>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta de Proyectos -->
                        <div class="carousel-item">
                            <div class="card carousel-card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">🔨 Proyectos</h5>
                                    <p class="card-text">Participa en proyectos innovadores y desarrolla nuevas habilidades. 🚀</p>
                                    <a href="lista_elementos.php?usuario_id=<?php echo $usuario_id; ?>&tipo=proyecto">Ver Proyectos</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

</div>
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
