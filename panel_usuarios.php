<?php
include('conexion.php');

// Obtener todos los usuarios junto con sus permisos (usando GROUP_CONCAT para evitar repetición)
$query = "
    SELECT u.usuario_id, u.nombre, u.apellidos, u.email, u.rol, 
           GROUP_CONCAT(DISTINCT a.permiso_materias) AS permiso_materias, 
           GROUP_CONCAT(DISTINCT a.permiso_juegos) AS permiso_juegos, 
           GROUP_CONCAT(DISTINCT a.permiso_proyectos) AS permiso_proyectos
    FROM usuarios u
    LEFT JOIN accesos a ON u.usuario_id = a.usuario_id
    GROUP BY u.usuario_id
";
$resultado = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Agregar Usuario</h2>
    <form action="procesar_usuario.php" method="POST">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Apellidos</label>
            <input type="text" name="apellidos" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Correo Electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Celular</label>
            <input type="text" name="celular" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="rol" class="form-control">
                <option value="usuario">Usuario</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- Permisos CRUD -->
        <h4>Permisos CRUD</h4>
        <div class="mb-3">
            <label>Materias</label>
            <div>
                <?php foreach (['crear', 'leer', 'actualizar', 'eliminar'] as $permiso) { ?>
                    <label>
                        <input type="checkbox" name="permiso_materias[]" value="<?php echo $permiso; ?>"> <?php echo ucfirst($permiso); ?>
                    </label>
                <?php } ?>
            </div>
        </div>
        <div class="mb-3">
            <label>Juegos</label>
            <div>
                <?php foreach (['crear', 'leer', 'actualizar', 'eliminar'] as $permiso) { ?>
                    <label>
                        <input type="checkbox" name="permiso_juegos[]" value="<?php echo $permiso; ?>"> <?php echo ucfirst($permiso); ?>
                    </label>
                <?php } ?>
            </div>
        </div>
        <div class="mb-3">
            <label>Proyectos</label>
            <div>
                <?php foreach (['crear', 'leer', 'actualizar', 'eliminar'] as $permiso) { ?>
                    <label>
                        <input type="checkbox" name="permiso_proyectos[]" value="<?php echo $permiso; ?>"> <?php echo ucfirst($permiso); ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <button type="submit" name="agregar" class="btn btn-primary mt-3">Agregar Usuario</button>
    </form>

    <hr>

    <h2>Lista de Usuarios</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Permisos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
                    <td><?php echo $row['usuario_id']; ?></td>
                    <td><?php echo $row['nombre'] . " " . $row['apellidos']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['rol']; ?></td>
                    <td>
                        <strong>Materias:</strong> <?php echo format_permisos($row['permiso_materias']); ?><br>
                        <strong>Juegos:</strong> <?php echo format_permisos($row['permiso_juegos']); ?><br>
                        <strong>Proyectos:</strong> <?php echo format_permisos($row['permiso_proyectos']); ?>
                    </td>
                    <td>
                        <a href="editar_usuario.php?id=<?php echo $row['usuario_id']; ?>" class="btn btn-warning">Editar</a>
                        <a href="procesar_usuario.php?eliminar=<?php echo $row['usuario_id']; ?>" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
// Función para formatear permisos
function format_permisos($permisos) {
    if (!$permisos) return 'Sin permisos';
    $permisos_array = explode(',', $permisos);
    return implode(', ', array_map('ucfirst', $permisos_array));
}
?>
</body>
</html>
