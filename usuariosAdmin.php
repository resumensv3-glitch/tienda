<?php
require 'vista/parte_superior_administrador.php';
require 'conexion.php';

$mensaje = "";

// Insertar usuario
if (isset($_POST['guardar'])) {
    $nombre = trim($_POST['nombre']);
    $usuario = trim($_POST['usuario']);
    $contraseña = trim($_POST['contraseña']);
    $rol = $_POST['rol'];

    $check = $mysqli->query("SELECT IDusuarios FROM usuarios WHERE usuario='$usuario'");
    if ($check && $check->num_rows > 0) {
        $mensaje = "⚠️ El usuario <strong>$usuario</strong> ya está registrado.";
    } else {
        $sql = "INSERT INTO usuarios (nombre, usuario, contraseña, rol)
                VALUES ('$nombre', '$usuario', '$contraseña', '$rol')";
        $mensaje = $mysqli->query($sql)
            ? "✅ Usuario <strong>$usuario</strong> registrado exitosamente."
            : "❌ Error al registrar: " . $mysqli->error;
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $mensaje = $mysqli->query("DELETE FROM usuarios WHERE IDusuarios=$id")
        ? "✅ Usuario eliminado correctamente."
        : "❌ Error al eliminar: " . $mysqli->error;
}

// Editar usuario
if (isset($_POST['editar'])) {
    $id = (int)$_POST['id'];
    $nombre = trim($_POST['nombre']);
    $usuario = trim($_POST['usuario']);
    $rol = $_POST['rol'];
    $contraseña = trim($_POST['contraseña']);

    $check = $mysqli->query("SELECT IDusuarios FROM usuarios WHERE usuario='$usuario' AND IDusuarios != $id");
    if ($check && $check->num_rows > 0) {
        $mensaje = "⚠️ El usuario <strong>$usuario</strong> ya está en uso.";
    } else {
        if ($contraseña == "") {
            $sql = "UPDATE usuarios SET nombre='$nombre', usuario='$usuario', rol='$rol' WHERE IDusuarios=$id";
        } else {
            $sql = "UPDATE usuarios SET nombre='$nombre', usuario='$usuario', contraseña='$contraseña', rol='$rol' WHERE IDusuarios=$id";
        }
        $mensaje = $mysqli->query($sql)
            ? "✅ Usuario actualizado correctamente."
            : "❌ Error al actualizar: " . $mysqli->error;
    }
}

// Consultar usuarios
$result = $mysqli->query("SELECT * FROM usuarios ORDER BY IDusuarios DESC");
?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Gestión de Usuarios</h1>

    <!-- Mensajes -->
    <?php if ($mensaje != ""): ?>
        <div class="alert <?= strpos($mensaje, '✅') !== false ? 'alert-success' : 'alert-warning'; ?>">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <!-- Formulario nuevo usuario -->
    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white">
            Registrar nuevo usuario
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-4 mb-2">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="password" name="contraseña" class="form-control" placeholder="Contraseña" required>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="rol" class="form-control" required>
                        <option value="">Rol...</option>
                        <option value="admin">Admin</option>
                        <option value="vendedor">Vendedor</option>
                        <option value="bodega">Bodega</option>
                    </select>
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="card shadow">
        <div class="card-header bg-danger text-white">
            Lista de usuarios
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['IDusuarios'] ?></td>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= htmlspecialchars($row['usuario']) ?></td>
                        <td><?= ucfirst($row['rol']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?= $row['IDusuarios'] ?>">Editar</button>
                            <a href="?eliminar=<?= $row['IDusuarios'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                        </td>
                    </tr>

                    <!-- Modal de edición -->
                    <div class="modal fade" id="editModal<?= $row['IDusuarios'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Usuario</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['IDusuarios'] ?>">
                                        <input type="text" name="nombre" class="form-control mb-2" value="<?= htmlspecialchars($row['nombre']) ?>" required>
                                        <input type="text" name="usuario" class="form-control mb-2" value="<?= htmlspecialchars($row['usuario']) ?>" required>
                                        <input type="password" name="contraseña" class="form-control mb-2" placeholder="Nueva contraseña (opcional)">
                                        <select name="rol" class="form-control" required>
                                            <option value="admin" <?= $row['rol']=='admin'?'selected':'' ?>>Admin</option>
                                            <option value="vendedor" <?= $row['rol']=='vendedor'?'selected':'' ?>>Vendedor</option>
                                            <option value="bodega" <?= $row['rol']=='bodega'?'selected':'' ?>>Bodega</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="editar" class="btn btn-success">Guardar cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require 'vista/parte_inferior.php'; ?>
