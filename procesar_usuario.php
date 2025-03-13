<?php
include('conexion.php');

// Agregar usuario
if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    // Obtener permisos seleccionados
    $permiso_materias = implode(',', $_POST['permiso_materias'] ?? []);
    $permiso_juegos = implode(',', $_POST['permiso_juegos'] ?? []);
    $permiso_proyectos = implode(',', $_POST['permiso_proyectos'] ?? []);

    // Insertar en la tabla de usuarios
    $query = "INSERT INTO usuarios (nombre, apellidos, email, celular, password, rol) 
              VALUES ('$nombre', '$apellidos', '$email', '$celular', '$password', '$rol')";
    mysqli_query($conn, $query);
    $usuario_id = mysqli_insert_id($conn);

    // Insertar en la tabla de accesos
    $query_accesos = "INSERT INTO accesos (usuario_id, permiso_materias, permiso_juegos, permiso_proyectos) 
                      VALUES ($usuario_id, '$permiso_materias', '$permiso_juegos', '$permiso_proyectos')";
    mysqli_query($conn, $query_accesos);

    header("Location: panel_usuarios.php");
    exit();
}

// Editar usuario
if (isset($_POST['editar'])) {
    $usuario_id = $_POST['usuario_id'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $rol = $_POST['rol'];

    // Obtener permisos seleccionados
    $permiso_materias = implode(',', $_POST['permiso_materias'] ?? []);
    $permiso_juegos = implode(',', $_POST['permiso_juegos'] ?? []);
    $permiso_proyectos = implode(',', $_POST['permiso_proyectos'] ?? []);

    // Actualizar en la tabla de usuarios
    $query = "UPDATE usuarios SET nombre='$nombre', apellidos='$apellidos', email='$email', celular='$celular', rol='$rol' 
              WHERE usuario_id=$usuario_id";
    mysqli_query($conn, $query);

    // Actualizar en la tabla de accesos
    $query_accesos = "UPDATE accesos SET permiso_materias='$permiso_materias', permiso_juegos='$permiso_juegos', 
                      permiso_proyectos='$permiso_proyectos' WHERE usuario_id=$usuario_id";
    mysqli_query($conn, $query_accesos);

    header("Location: panel_usuarios.php");
    exit();
}

// Eliminar usuario y sus permisos
if (isset($_GET['eliminar'])) {
    $usuario_id = $_GET['eliminar'];
    mysqli_query($conn, "DELETE FROM accesos WHERE usuario_id = $usuario_id");
    mysqli_query($conn, "DELETE FROM usuarios WHERE usuario_id = $usuario_id");
    header("Location: panel_usuarios.php");
    exit();
}
?>
