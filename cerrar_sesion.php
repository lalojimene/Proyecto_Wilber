<?php
// Eliminar la cookie que contiene el token JWT
setcookie("token", "", time() - 3600, "/"); // Establece la cookie con una fecha de expiración pasada

// Redirigir al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
?>
