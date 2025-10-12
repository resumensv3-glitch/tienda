<?php
//Manda a llamar la parte superior de la pagina
require 'vista/parte_superior_vendedor.php';
require 'conexion.php';

// Insertar cliente
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $dui = $_POST['dui'];

    $sql = "INSERT INTO Clientes (nombre, apellido, email, telefono, DUI) 
            VALUES ('$nombre', '$apellido', '$email', '$telefono', '$dui')";
    $mysqli->query($sql);
}

// Eliminar cliente
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM Clientes WHERE IDcliente=$id";
    $mysqli->query($sql);
}

// Editar cliente
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $dui = $_POST['dui'];

    $sql = "UPDATE Clientes SET 
                nombre='$nombre',
                apellido='$apellido',
                email='$email',
                telefono='$telefono',
                DUI='$dui'
            WHERE IDcliente=$id";
    $mysqli->query($sql);
}

// Consultar clientes
$result = $mysqli->query("SELECT * FROM Clientes ORDER BY IDcliente DESC");


?>
<!-- Muestra el contenido principal de la pagina  -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                
<h2>Registrar Cliente</h2>
<form method="POST" class="row g-3">
    <div class="col-md-6">
        <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
    </div>
    <div class="col-md-6">
        <input type="text" name="apellido" class="form-control" placeholder="Apellido" required>
    </div>
    <div class="col-md-6">
        <input type="email" name="email" class="form-control" placeholder="Correo">
    </div>
    <div class="col-md-3">
        <input type="text" name="telefono" class="form-control" placeholder="Teléfono">
    </div>
    <div class="col-md-3">
        <input type="text" name="dui" class="form-control" placeholder="DUI">
    </div>
    <div class="col-12">
        <button type="submit" name="guardar" class="btn btn-primary">Guardar</button>
    </div>
</form>

<hr>

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
                <!-- Botón editar (abre modal) -->
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['IDcliente'] ?>">Editar</button>
                <a href="clientesVende.php?eliminar=<?= $row['IDcliente'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este cliente?')">Eliminar</a>
            </td>
        </tr>

        <!-- Modal de edición -->
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
                  <input type="text" name="nombre" class="form-control mb-2" value="<?= $row['nombre'] ?>" placeholder="Ingrese el DUI" required>
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
    </tbody>
</table>
               
            </div>
            <!-- End of Main Content -->



<!-- final del menu principal de la pagina  -->





<?php
//Manda a llamar la parte inferior del codigo
require 'vista/parte_inferior.php';