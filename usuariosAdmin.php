<?php
//Manda a llamar la parte superior de la pagina
require 'vista/parte_superior_administrador.php';

// Insertar usuario
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // encriptar
    $rol = $_POST['rol'];

    $sql = "INSERT INTO usuarios (nombre, usuario, contraseña, rol)
            VALUES ('$nombre', '$usuario', '$contraseña', '$rol')";
    $mysqli->query($sql);
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM usuarios WHERE IDusuarios=$id";
    $mysqli->query($sql);
}

// Editar usuario
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];

    // Si no se cambia contraseña
    if (empty($_POST['contraseña'])) {
        $sql = "UPDATE usuarios SET 
                    nombre='$nombre', usuario='$usuario', rol='$rol'
                WHERE IDusuarios=$id";
    } else {
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET 
                    nombre='$nombre', usuario='$usuario', contraseña='$contraseña', rol='$rol'
                WHERE IDusuarios=$id";
    }
    $mysqli->query($sql);
}

// Consultar usuarios
$result = $mysqli->query("SELECT * FROM usuarios ORDER BY IDusuarios DESC");

?>
<!-- Muestra el contenido principal de la pagina  -->

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Usuarios</h1>

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
            <!-- Select en lugar de dropdown -->
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
                    <!-- Botón editar -->
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal<?= $row['IDusuarios'] ?>">Editar</button>
                    <!-- Botón eliminar -->
                    <a href="usuarios.php?eliminar=<?= $row['IDusuarios'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
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

                      <!-- Select en lugar de dropdown -->
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
