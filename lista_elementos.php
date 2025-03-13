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

// Obtener el usuario_id y tipo de la URL
$usuario_id = isset($_GET['usuario_id']) ? (int)$_GET['usuario_id'] : 0;
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if ($usuario_id === 0 || empty($tipo)) {
    echo "Información incompleta.";
    exit();
}

// Consultar nombre del usuario
$sql_usuario = "SELECT nombre FROM usuarios WHERE usuario_id = $usuario_id LIMIT 1";
$result_usuario = $conn->query($sql_usuario);
$nombre_usuario = ($result_usuario->num_rows > 0) ? $result_usuario->fetch_assoc()['nombre'] : "Usuario desconocido";

// Consultar permisos del usuario para la entidad seleccionada
$sql_permisos = "SELECT permiso_materias, permiso_juegos, permiso_proyectos FROM accesos WHERE usuario_id = $usuario_id LIMIT 1";
$result_permisos = $conn->query($sql_permisos);
$permisos = ($result_permisos->num_rows > 0) ? $result_permisos->fetch_assoc() : [];

// Determinar los permisos según el tipo
$permisos_crud = [];
switch ($tipo) {
    case 'materia':
        $tabla = 'materias';
        $columna_id = 'materia_id';
        $titulo = 'Materias';
        $permisos_crud = explode(',', $permisos['permiso_materias']);
        break;
    case 'juego':
        $tabla = 'juegos';
        $columna_id = 'juego_id';
        $titulo = 'Juegos';
        $permisos_crud = explode(',', $permisos['permiso_juegos']);
        break;
    case 'proyecto':
        $tabla = 'proyectos';
        $columna_id = 'proyecto_id';
        $titulo = 'Proyectos';
        $permisos_crud = explode(',', $permisos['permiso_proyectos']);
        break;
    default:
        echo "Tipo de entidad desconocido.";
        exit();
}

// Verificar permisos individuales
$puede_crear = in_array('crear', $permisos_crud);
$puede_leer = in_array('leer', $permisos_crud);
$puede_actualizar = in_array('actualizar', $permisos_crud);
$puede_eliminar = in_array('eliminar', $permisos_crud);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> de <?php echo htmlspecialchars($nombre_usuario); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            margin-top: 30px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-success {
            margin-bottom: 15px;
        }
        .card:hover {
            transform: scale(1.02);
            transition: 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2><?php echo $titulo; ?> de <?php echo htmlspecialchars($nombre_usuario); ?></h2>
                
                <!-- Botón de agregar (solo si tiene permiso) -->
                <?php if ($puede_crear): ?>
                    <a href="agregar.php?tipo=<?php echo $tipo; ?>" class="btn btn-success">Agregar <?php echo $titulo; ?></a>
                <?php endif; ?>

                <div class="row g-4" id="lista-elementos">
                    <!-- Las tarjetas se cargarán aquí -->
                </div>
            </div>
        </div>
    </div>

    <script>
        async function cargarElementos() {
            try {
                const usuarioId = <?php echo $usuario_id; ?>;
                const tipo = "<?php echo $tipo; ?>";

                const response = await fetch(`lista_ajax.php?usuario_id=${usuarioId}&tipo=${tipo}`);
                if (!response.ok) throw new Error('Error al cargar los datos');

                const data = await response.json();
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                const listaElementos = document.getElementById('lista-elementos');

                if (data.elementos.length === 0) {
                    listaElementos.innerHTML = "<p>No hay elementos disponibles.</p>";
                    return;
                }

                listaElementos.innerHTML = "";
                data.elementos.forEach(item => {
                    const col = document.createElement('div');
                    col.classList.add('col-md-4');
                    let botones = "";

                    <?php if ($puede_leer): ?>
                        botones += `<a href="descripcion.php?id=${item.id}&tipo=${tipo}" class="btn btn-primary">Ver</a> `;
                    <?php endif; ?>
                    
                    <?php if ($puede_actualizar): ?>
                        botones += `<a href="editar.php?id=${item.id}&tipo=${tipo}" class="btn btn-warning">Editar</a> `;
                    <?php endif; ?>
                    
                    <?php if ($puede_eliminar): ?>
                        botones += `<a href="eliminar.php?id=${item.id}&tipo=${tipo}" class="btn btn-danger">Eliminar</a>`;
                    <?php endif; ?>

                    col.innerHTML = `
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">${item.nombre}</h5>
                                <p class="card-text">Descripción breve del ${tipo}.</p>
                                ${botones}
                            </div>
                        </div>
                    `;
                    listaElementos.appendChild(col);
                });
            } catch (error) {
                console.error('Error en la solicitud:', error);
                alert('Hubo un problema al obtener los datos.');
            }
        }

        window.onload = cargarElementos;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
