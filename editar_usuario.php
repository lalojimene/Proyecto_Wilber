<?php
include 'conexion.php';

if (isset($_POST['editar'])) {
    // Obtener los datos del formulario
    $usuario_id = $_POST['usuario_id'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $rol = $_POST['rol'];

    // Actualizar los datos del usuario
    $query = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos', email = '$email', celular = '$celular', rol = '$rol' WHERE usuario_id = $usuario_id";
    mysqli_query($conn, $query);

    // Actualizar los permisos CRUD
    $permiso_materias = isset($_POST['permiso_materias']) ? implode(',', $_POST['permiso_materias']) : '';
    $permiso_juegos = isset($_POST['permiso_juegos']) ? implode(',', $_POST['permiso_juegos']) : '';
    $permiso_proyectos = isset($_POST['permiso_proyectos']) ? implode(',', $_POST['permiso_proyectos']) : '';

    // Actualizar o insertar permisos en la tabla 'accesos'
    $sql_permisos = "REPLACE INTO accesos (usuario_id, permiso_materias, permiso_juegos, permiso_proyectos) 
                     VALUES ($usuario_id, '$permiso_materias', '$permiso_juegos', '$permiso_proyectos')";
    mysqli_query($conn, $sql_permisos);

    // Redirigir a panel_usuarios.php
    header("Location: panel_usuarios.php");
    exit;
}

// Verificar si se está editando un usuario específico
if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];
    $query = "SELECT * FROM usuarios WHERE usuario_id = $usuario_id";
    $result = mysqli_query($conn, $query);
    $usuario = mysqli_fetch_assoc($result);
    
    // Obtener los permisos del usuario
    $sql_permisos = "SELECT permiso_materias, permiso_juegos, permiso_proyectos FROM accesos WHERE usuario_id = $usuario_id LIMIT 1";
    $result_permisos = mysqli_query($conn, $sql_permisos);
    $permisos = mysqli_fetch_assoc($result_permisos);
    
    // Convertir permisos a arrays
    $permiso_materias = explode(',', $permisos['permiso_materias']);
    $permiso_juegos = explode(',', $permisos['permiso_juegos']);
    $permiso_proyectos = explode(',', $permisos['permiso_proyectos']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Usuario</h2>
    <form action="procesar_usuario.php" method="POST">
        <input type="hidden" name="usuario_id" value="<?php echo $usuario['usuario_id']; ?>">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Apellidos</label>
            <input type="text" name="apellidos" class="form-control" value="<?php echo $usuario['apellidos']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $usuario['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Celular</label>
            <input type="text" name="celular" class="form-control" value="<?php echo $usuario['celular']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="rol" class="form-control">
                <option value="usuario" <?php if ($usuario['rol'] == 'usuario') echo 'selected'; ?>>Usuario</option>
                <option value="admin" <?php if ($usuario['rol'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
        </div>

        <!-- Permisos CRUD -->
        <h4>Permisos CRUD</h4>
        <div class="mb-3">
            <label>Materias</label>
            <div>
                <?php foreach (['crear', 'leer', 'actualizar', 'eliminar'] as $permiso) { ?>
                    <label>
                        <input type="checkbox" name="permiso_materias[]" value="<?php echo $permiso; ?>" <?php if (in_array($permiso, $permiso_materias)) echo 'checked'; ?>> <?php echo ucfirst($permiso); ?>
                    </label>
                <?php } ?>
            </div>
        </div>
        <div class="mb-3">
            <label>Juegos</label>
            <div>
                <?php foreach (['crear', 'leer', 'actualizar', 'eliminar'] as $permiso) { ?>
                    <label>
                        <input type="checkbox" name="permiso_juegos[]" value="<?php echo $permiso; ?>" <?php if (in_array($permiso, $permiso_juegos)) echo 'checked'; ?>> <?php echo ucfirst($permiso); ?>
                    </label>
                <?php } ?>
            </div>
        </div>
        <div class="mb-3">
            <label>Proyectos</label>
            <div>
                <?php foreach (['crear', 'leer', 'actualizar', 'eliminar'] as $permiso) { ?>
                    <label>
                        <input type="checkbox" name="permiso_proyectos[]" value="<?php echo $permiso; ?>" <?php if (in_array($permiso, $permiso_proyectos)) echo 'checked'; ?>> <?php echo ucfirst($permiso); ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <button type="submit" name="editar" class="btn btn-success">Guardar Cambios</button>
        <a href="panel_usuarios.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
