<?php
session_start();

if (isset($_GET['session_conflict'])) {
    echo "<script>alert('Ya tienes una sesión activa en otro dispositivo. Se ha cerrado automáticamente.');</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Font Awesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos personalizados */
        body {
            background: linear-gradient(135deg,rgb(150, 199, 168),rgb(199, 231, 233));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            background: #ffffff;
            padding: 20px;
        }

        h3 {
            color: #333;
            font-weight: 600;
            text-align: center;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.5);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Ícono de ojo para la contraseña */
        .password-container {
            position: relative;
        }

        #toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555;
        }

        #toggle-password:hover {
            color: #007bff;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow-lg">
                    <h3 class="text-center mb-3">Iniciar Sesión</h3>
                    
                    <form action="procesar_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3 position-relative">
                            <label class="form-label">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            <!-- Icono para mostrar/ocultar la contraseña -->
                            <i id="toggle-password" class="fas fa-eye position-absolute top-50 end-0 translate-middle-y pe-3" style="cursor: pointer;"></i>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                    </form>

                    <p class="mt-3 text-center">
                        <a href="opciones_recuperacion.php">¿Olvidaste tu contraseña?</a>
                    </p>
                    
                    <p class="mt-3 text-center">
                        ¿No tienes cuenta? <a href="register.php">Regístrate</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script para cambiar la visibilidad de la contraseña -->
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const icon = this;

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
