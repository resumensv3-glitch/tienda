<?php
session_start();
require 'conexion.php';

// Función para cargar la cabecera según rol
function cargarCabecera() {
    if (!isset($_SESSION['rol'])) {
        header("Location: login.php");
        exit;
    }

    switch ($_SESSION['rol']) {
        case 'admin':
            require 'vista/parte_superior_administrador.php';
            break;
        case 'vendedor':
            require 'vista/parte_superior_vendedor.php';
            break;
        default:
            header("Location: login.php");
            exit;
    }
}

// Llamamos la función para mostrar la cabecera correcta
cargarCabecera();

// ========================
// ELIMINAR CLIENTE
// ========================
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $stmt = $mysqli->prepare("DELETE FROM Clientes WHERE IDcliente = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: clientes.php");
    exit;
}

// ========================
// EDITAR CLIENTE
// ========================
if (isset($_POST['editar'])) {
    $id = (int)$_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $dui = $_POST['dui'];

    $stmt = $mysqli->prepare("UPDATE Clientes SET nombre=?, apellido=?, email=?, telefono=?, DUI=? WHERE IDcliente=?");
    $stmt->bind_param("sssssi", $nombre, $apellido, $email, $telefono, $dui, $id);
    $stmt->execute();

    header("Location: clientes.php");
    exit;
}

// ========================
// CONSULTAR CLIENTES
// ========================
$result = $mysqli->query("SELECT * FROM Clientes ORDER BY IDcliente DESC");
?>

<div class="container-fluid">
    <h2>Lista de Clientes</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre completo</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>DUI</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['IDcliente'] ?></td>
                <td><?= $row['nombre'] . " " . $row['apellido'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['telefono'] ?></td>
                <td><?= $row['DUI'] ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['IDcliente'] ?>">Editar</button>
                    <a href="clientes.php?eliminar=<?= $row['IDcliente'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este cliente?')">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- MODALES DE EDICIÓN -->
<?php
$result->data_seek(0); // reinicia el puntero
while($row = $result->fetch_assoc()):
?>
<div class="modal fade" id="editModal<?= $row['IDcliente'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Editar Cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="<?= $row['IDcliente'] ?>">
          <input type="text" name="nombre" class="form-control mb-2" value="<?= $row['nombre'] ?>" required>
          <input type="text" name="apellido" class="form-control mb-2" value="<?= $row['apellido'] ?>" required>
          <input type="email" name="email" class="form-control mb-2" value="<?= $row['email'] ?>">
          <input type="text" name="telefono" class="form-control mb-2" value="<?= $row['telefono'] ?>">
          <input type="text" name="dui" class="form-control mb-2" value="<?= $row['DUI'] ?>">
        </div>
        <div class="modal-footer">
          <button type="submit" name="editar" class="btn btn-success">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endwhile; ?>

<?php require 'vista/parte_inferior.php'; ?>
