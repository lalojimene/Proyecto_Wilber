<?php
// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root"; // 
$password = ""; 
$dbname = "gerardo_db"; 

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Lista de usuarios
$usuarios = [
    ['usuario_id' => 6, 'nombre' => 'Gerardo', 'apellidos' => 'Pérez García', 'email' => 'gerardoperez@example.com', 'celular' => '9192049623', 'pregunta1' => 1, 'respuesta1' => 'chispo', 'pregunta2' => 5, 'respuesta2' => 'chayo', 'rol' => 'usuario'],
    ['usuario_id' => 7, 'nombre' => 'Josue', 'apellidos' => 'López Sánchez', 'email' => 'josuelopez@example.com', 'celular' => '9192049623', 'pregunta1' => 1, 'respuesta1' => 'chispo', 'pregunta2' => 5, 'respuesta2' => 'chayo', 'rol' => 'usuario'],
    ['usuario_id' => 8, 'nombre' => 'Alexis', 'apellidos' => 'Martínez Ruiz', 'email' => 'alexismartinez@example.com', 'celular' => '9192049623', 'pregunta1' => 1, 'respuesta1' => 'chispo', 'pregunta2' => 5, 'respuesta2' => 'chayo', 'rol' => 'usuario'],
    ['usuario_id' => 9, 'nombre' => 'Yahir', 'apellidos' => 'Vega Pérez', 'email' => 'yahirvega@example.com', 'celular' => '9192049623', 'pregunta1' => 1, 'respuesta1' => 'chispo', 'pregunta2' => 5, 'respuesta2' => 'chayo', 'rol' => 'usuario'],
    ['usuario_id' => 10, 'nombre' => 'Wilber', 'apellidos' => 'Ramírez Sánchez', 'email' => 'wilberramirez@example.com', 'celular' => '9192049623', 'pregunta1' => 1, 'respuesta1' => 'chispo', 'pregunta2' => 5, 'respuesta2' => 'chayo', 'rol' => 'admin'],
];

foreach ($usuarios as $usuario) {
    // Cifrar la contraseña 'qwerty' para todos los usuarios
    $hashedPassword = password_hash('qwerty', PASSWORD_DEFAULT);
    
    // Preparar la consulta SQL para insertar el usuario
    $sql = "INSERT INTO `usuarios` (`usuario_id`, `nombre`, `apellidos`, `email`, `celular`, `pregunta1`, `respuesta1`, `pregunta2`, `respuesta2`, `password`, `rol`, `created_at`, `token`, `token_expira`) 
            VALUES ('" . $usuario['usuario_id'] . "', '" . $usuario['nombre'] . "', '" . $usuario['apellidos'] . "', '" . $usuario['email'] . "', '" . $usuario['celular'] . "', " . $usuario['pregunta1'] . ", '" . $usuario['respuesta1'] . "', " . $usuario['pregunta2'] . ", '" . $usuario['respuesta2'] . "', '" . $hashedPassword . "', '" . $usuario['rol'] . "', NOW(), NULL, NULL)";

    // Ejecutar la consulta SQL
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo registro creado correctamente para: " . $usuario['nombre'] . "<br>";
    } else {
        echo "Error al insertar el usuario " . $usuario['nombre'] . ": " . $conn->error . "<br>";
    }
}

// Cerrar la conexión
$conn->close();
?>
