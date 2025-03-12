<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'conexion.php';

    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar si las contraseñas coinciden
    if ($password === $confirm_password) {
        $nueva_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "UPDATE usuarios SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nueva_password, $email);

        if ($stmt->execute()) {
            // Redirigir a login.php si se actualiza correctamente
            header("Location: login.php");
            exit();
        } else {
            echo "Hubo un error al actualizar la contraseña.";
        }
    } else {
        echo "Las contraseñas no coinciden.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 35px;
            z-index: 2;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow-lg">
                    <h3 class="text-center mb-3">Nueva Contraseña</h3>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3 position-relative">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <i class="bi bi-eye-slash password-toggle" id="togglePassword1"></i>
                        </div>
                        <div class="mb-3 position-relative">
                            <label class="form-label">Repetir Contraseña</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            <i class="bi bi-eye-slash password-toggle" id="togglePassword2"></i>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Actualizar Contraseña</button>
                    </form>
                    <p class="mt-3 text-center">
                        <a href="login.php">Volver al inicio</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const togglePassword1 = document.querySelector('#togglePassword1');
        const password = document.querySelector('#password');
        
        togglePassword1.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        const togglePassword2 = document.querySelector('#togglePassword2');
        const confirm_password = document.querySelector('#confirm_password');

        togglePassword2.addEventListener('click', function () {
            const type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
            confirm_password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
