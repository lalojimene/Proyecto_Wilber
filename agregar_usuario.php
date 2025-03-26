<?php
include('conexion.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
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
</div>
</body>
</html>
