<?php
require 'conexion.php';
// Llama a la parte superior de la vista
require 'vista/parte_superior_bodega.php'; 

$mensaje = "";

// --- Eliminar categoría ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $mysqli->query("DELETE FROM categorias WHERE IDcategorias = $id");
    $mensaje = "Categoría eliminada.";
}

// --- Editar categoría ---
if (isset($_POST['editar'])) {
    $id          = $_POST['id'];
    $categoria   = $_POST['categoria'];
    $estilo      = $_POST['estilo'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE categorias SET categoria=?, estilo=?, descripcion=? WHERE IDcategorias=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssi", $categoria, $estilo, $descripcion, $id);

    if ($stmt->execute()) {
        $mensaje = "Categoría actualizada correctamente.";
    } else {
        $mensaje = "Error al actualizar: " . $stmt->error;
    }
}

// --- Agregar nueva categoría ---
if (isset($_POST['guardar'])) {
    $categoria   = $_POST['categoria'];
    $estilo      = $_POST['estilo'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO categorias (categoria, estilo, descripcion) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $categoria, $estilo, $descripcion);

    if ($stmt->execute()) {
        $mensaje = "Categoría agregada correctamente.";
    } else {
        $mensaje = "Error al guardar: " . $stmt->error;
    }
}

// --- Obtener todas las categorías ---
$categorias_result = $mysqli->query("SELECT * FROM categorias");
?>


 <!-- Aqui inicia el contenido -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">CATEGORIAS</h1>


<div class="container mt-4">

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <h2>Agregar Categoría</h2>
    <form method="POST">
        <div class="form-group">
            <label>Categoría</label>
            <input type="text" name="categoria" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Estilo</label>
            <input type="text" name="estilo" class="form-control">
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>
        <button type="submit" name="guardar" class="btn btn-danger">Guardar</button>
    </form>

    <hr>

    <h2>Listado de Categorías</h2>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Categoría</th>
            <th>Estilo</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($cat = $categorias_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $cat['IDcategorias']; ?></td>
                <td><?php echo $cat['categoria']; ?></td>
                <td><?php echo $cat['estilo']; ?></td>
                <td><?php echo $cat['descripcion']; ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editar<?php echo $cat['IDcategorias']; ?>">Editar</button>
                    <a href="categoriasBod.php?eliminar=<?php echo $cat['IDcategorias']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</a>
                </td>
            </tr>

            <!-- Modal para editar categoría -->
            <div class="modal fade" id="editar<?php echo $cat['IDcategorias']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Categoría</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?php echo $cat['IDcategorias']; ?>">
                                <div class="form-group">
                                    <label>Categoría</label>
                                    <input type="text" name="categoria" class="form-control" value="<?php echo $cat['categoria']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Estilo</label>
                                    <input type="text" name="estilo" class="form-control" value="<?php echo $cat['estilo']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="descripcion" class="form-control"><?php echo $cat['descripcion']; ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="editar" class="btn btn-success">Guardar cambios</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
                <!-- /.container-fluid -->




<!-- /Mandamos a mandar a llamar la parte inferior -->
<?php
require 'vista/parte_inferior.php';
?>    

