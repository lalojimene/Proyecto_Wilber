<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opciones de Recuperación de Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow-lg">
                    <h3 class="text-center mb-3">Opciones de Recuperación</h3>
                    <p class="text-center">Selecciona el método para recuperar tu contraseña:</p>

                    <div class="list-group">
                        <a href="recuperar_contraseña.php" class="list-group-item list-group-item-action">Recuperar por Correo Electrónico</a>
                        <a href="recuperar_pregunta.php" class="list-group-item list-group-item-action">Recuperar por Pregunta Secreta</a>
                        <a href="recuperar_telefono_sms.php" class="list-group-item list-group-item-action">Recuperar por Número de Teléfono</a>
                    </div>

                    <p class="mt-3 text-center">
                        <a href="login.php">Volver al inicio</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
