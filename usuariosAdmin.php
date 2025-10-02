<?php
// Manda a llamar la parte superior de la pagina
require 'vista/parte_superior_administrador.php';

$mensaje = "";

// Insertar usuario
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $rol = $_POST['rol'];

    // Verificar si el usuario ya existe
    $check = $mysqli->query("SELECT IDusuarios FROM usuarios WHERE usuario='$usuario'");

    if ($check && $check->num_rows > 0) {
        // Mensaje de usuario duplicado
        $mensaje = "⚠️ El usuario <strong>$usuario</strong> ya está registrado, elija otro.";
    } else {
        // Insertar nuevo usuario
        $sql = "INSERT INTO usuarios (nombre, usuario, contraseña, rol)
                VALUES ('$nombre', '$usuario', '$contraseña', '$rol')";
        if ($mysqli->query($sql)) {
            $mensaje = "✅ Usuario <strong>$usuario</strong> registrado exitosamente.";
        } else {
            $mensaje = "❌ Error al registrar: " . $mysqli->error;
        }
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM usuarios WHERE IDusuarios=$id";
    if ($mysqli->query($sql)) {
        $mensaje = "✅ Usuario eliminado correctamente.";
    } else {
        $mensaje = "❌ Error al eliminar: " . $mysqli->error;
    }
}

// Editar usuario
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];

    // Verificar duplicado al editar (excepto el mismo ID)
    $check = $mysqli->query("SELECT IDusuarios FROM usuarios WHERE usuario='$usuario' AND IDusuarios != $id");
    if ($check && $check->num_rows > 0) {
        $mensaje = "⚠️ El usuario <strong>$usuario</strong> ya está en uso por otro registro.";
    } else {
        if (empty($_POST['contraseña'])) {
            $sql = "UPDATE usuarios SET 
                        nombre='$nombre', usuario='$usuario', rol='$rol'
                    WHERE IDusuarios=$id";
        } else {
            $contraseña = $_POST['contraseña'];
            $sql = "UPDATE usuarios SET 
                        nombre='$nombre', usuario='$usuario', contraseña='$contraseña', rol='$rol'
                    WHERE IDusuarios=$id";
        }

        if ($mysqli->query($sql)) {
            $mensaje = "✅ Usuario actualizado correctamente.";
        } else {
            $mensaje = "❌ Error al actualizar: " . $mysqli->error;
        }
    }
}

// Consultar usuarios
$result = $mysqli->query("SELECT * FROM usuarios ORDER BY IDusuarios DESC");
?>

<!-- Muestra el contenido principal de la pagina  -->

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Usuarios</h1>

    <!-- Mensajes -->
    <?php if ($mensaje != ""): ?>
        <div class="alert <?php echo strpos($mensaje,'✅') !== false ? 'alert-success' : 'alert-danger'; ?>">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <h2>Registrar Usuario</h2>
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
                <option value="">Seleccionar Rol</option>
                <option value="admin">Admin</option>
                <option value="vendedor">Vendedor</option>
                <option value="bodega">Bodega</option>
            </select>
        </div>
        <div class="col-12 mt-2">
            <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
        </div>
    </form>

    <hr>

    <h2>Lista de Usuarios</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['IDusuarios'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['usuario'] ?></td>
                <td><?= ucfirst($row['rol']) ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal<?= $row['IDusuarios'] ?>">Editar</button>
                    <a href="usuariosAdmin.php?eliminar=<?= $row['IDusuarios'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
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
                      <input type="text" name="nombre" class="form-control mb-2" value="<?= $row['nombre'] ?>" required>
                      <input type="text" name="usuario" class="form-control mb-2" value="<?= $row['usuario'] ?>" required>
                      <input type="password" name="contraseña" class="form-control mb-2" placeholder="Nueva contraseña (opcional)">
                      <select name="rol" class="form-control mb-2" required>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</div>
<!-- End of Main Content -->

<?php
//Manda a llamar la parte inferior del codigo
require 'vista/parte_inferior.php';
?>
