<?php
require 'config.php';

if (!isset($_GET["token"])) {
    die("Token inválido.");
}

$token = $_GET["token"];

// Verificar si el token es válido
$stmt = $conn->prepare("SELECT usuario_id FROM usuarios WHERE token = ? AND token_expira > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    die("Token inválido o expirado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function togglePassword(id) {
            var input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }

        function validatePasswords() {
            var pass1 = document.getElementById("password").value;
            var pass2 = document.getElementById("confirm_password").value;
            if (pass1 !== pass2) {
                alert("Las contraseñas no coinciden.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow-lg">
                    <h3 class="text-center mb-3">Restablecer Contraseña</h3>
                    
                    <form action="actualizar_contraseña.php" method="POST" onsubmit="return validatePasswords()">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">👁️</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Repetir Contraseña</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_password')">👁️</button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Restablecer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
