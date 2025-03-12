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
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav" id="userMenu">
                        <!-- Los datos del menú se cargarán aquí mediante JS -->
                    </div>
                </div>
            </nav>
        </div>
        
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <!-- Aquí va el contenido principal -->
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Función para cargar los accesos
            function cargarAccesos() {
                fetch('fetch_accesos.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        // Obtenemos el contenedor del menú lateral
                        const userMenu = document.getElementById('userMenu');

                        for (const [usuarioId, usuario] of Object.entries(data)) {
                            let bloqueado = usuarioRol === 'usuario' && usuarioId !== <?php echo $usuarioActual['usuario_id']; ?>;
                            let icono = bloqueado ? '<i class="fas fa-lock"></i>' : '<i class="fas fa-user"></i>';
                            let dataToggle = bloqueado ? '' : 'data-toggle="collapse"';
                            let dataTarget = bloqueado ? '' : `data-target="#usuario${usuarioId}"`;

                            // Crear el HTML para el usuario y sus accesos
                            const usuarioHTML = `
                                <a class="nav-link" href="#" ${dataToggle} ${dataTarget} aria-expanded="false" aria-controls="usuario${usuarioId}">
                                    <div class="sb-nav-link-icon">${icono}</div>
                                    ${usuario.nombre}
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="usuario${usuarioId}">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        ${usuario.accesos.map(acceso => {
                                            let materia = acceso.materia ? `<a class="nav-link ${bloqueado ? 'text-muted' : ''}" href="#">${acceso.materia}</a>` : '';
                                            let juego = acceso.juego ? `<a class="nav-link ${bloqueado ? 'text-muted' : ''}" href="#">${acceso.juego}</a>` : '';
                                            let proyecto = acceso.proyecto ? `<a class="nav-link ${bloqueado ? 'text-muted' : ''}" href="#">${acceso.proyecto}</a>` : '';
                                            return `${materia} ${juego} ${proyecto}`;
                                        }).join('')}
                                    </nav>
                                </div>
                            `;

                            userMenu.innerHTML += usuarioHTML;
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar los accesos:', error);
                    });
            }

            // Llamamos a la función para cargar los accesos al iniciar
            cargarAccesos();
        });
    </script>
</body>
</html>
