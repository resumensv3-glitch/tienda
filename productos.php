<?php
require 'conexion.php';
//Manda a llamar la parte superior de la pagina
require 'vista/parte_superior_bodega.php';

$mensaje = "";

// --- Eliminar producto ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);

    // Buscar la ruta de la imagen para borrarla también
    $res = $mysqli->query("SELECT imagen FROM productos WHERE IDproductos = $id");
    if ($res && $fila = $res->fetch_assoc()) {
        if (file_exists($fila['imagen'])) {
            unlink($fila['imagen']); // elimina el archivo físico
        }
    }

    $mysqli->query("DELETE FROM productos WHERE IDproductos = $id");
    $mensaje = "Producto eliminado.";
}

// --- Editar producto ---
if (isset($_POST['editar'])) {
    $id         = $_POST['id'];
    $producto   = $_POST['producto'];
    $categorias = $_POST['categorias'];
    $stock      = $_POST['stock'];
    $marca      = $_POST['marca'];
    $talla      = $_POST['talla'];
    $precio     = $_POST['precio'];

    $rutaImagen = null;

    // Si subieron nueva imagen
    if (!empty($_FILES['imagen']['name'])) {
        $nombreArchivo = $_FILES['imagen']['name'];
        $tmpName = $_FILES['imagen']['tmp_name'];

        $carpetaDestino = "uploads/";
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $rutaImagen = $carpetaDestino . time() . "_" . basename($nombreArchivo);
        move_uploaded_file($tmpName, $rutaImagen);

        // Borrar imagen anterior
        $res = $mysqli->query("SELECT imagen FROM productos WHERE IDproductos = $id");
        if ($res && $fila = $res->fetch_assoc()) {
            if (file_exists($fila['imagen'])) {
                unlink($fila['imagen']);
            }
        }

        $sql = "UPDATE productos SET producto=?, categorias=?, imagen=?, stock=?, marca=?, talla=?, precio=? WHERE IDproductos=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisissdi", $producto, $categorias, $rutaImagen, $stock, $marca, $talla, $precio, $id);
    } else {
        $sql = "UPDATE productos SET producto=?, categorias=?, stock=?, marca=?, talla=?, precio=? WHERE IDproductos=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisissd", $producto, $categorias, $stock, $marca, $talla, $precio, $id);
    }

    if ($stmt->execute()) {
        $mensaje = "Producto actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar: " . $stmt->error;
    }
}

// --- Insertar nuevo producto ---
if (isset($_POST['guardar'])) {
    $producto   = $_POST['producto'];
    $categorias = $_POST['categorias'];
    $stock      = $_POST['stock'];
    $marca      = $_POST['marca'];
    $talla      = $_POST['talla'];
    $precio     = $_POST['precio'];

    $nombreArchivo = $_FILES['imagen']['name'];
    $tmpName = $_FILES['imagen']['tmp_name'];

    $carpetaDestino = "uploads/";
    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0777, true);
    }

    $rutaImagen = $carpetaDestino . time() . "_" . basename($nombreArchivo);

    if (move_uploaded_file($tmpName, $rutaImagen)) {
        $sql = "INSERT INTO productos (producto, categorias, imagen, stock, marca, talla, precio)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisissd", $producto, $categorias, $rutaImagen, $stock, $marca, $talla, $precio);

        if ($stmt->execute()) {
            $mensaje = "Producto agregado correctamente.";
        } else {
            $mensaje = "Error al guardar en BD: " . $stmt->error;
        }
    } else {
        $mensaje = "Error al subir la imagen.";
    }
}

// --- Obtener categorías ---
$categorias_result = $mysqli->query("SELECT IDcategorias, categoria FROM categorias");

// --- Obtener productos ---
$productos_result = $mysqli->query("SELECT p.*, c.categoria AS nombre_categoria 
                                   FROM productos p 
                                   LEFT JOIN categorias c ON p.categorias = c.IDcategorias");
?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">PRODUCTOS</h1>




                   <div class="container mt-4">

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <h2>Agregar Producto</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Producto</label>
            <input type="text" name="producto" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Categoría</label>
            <select name="categorias" class="form-control" required>
                <option value="">-- Selecciona una categoría --</option>
                <?php while ($row = $categorias_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['IDcategorias']; ?>">
                        <?php echo $row['categoria']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Imagen</label>
            <input type="file" name="imagen" class="form-control-file" required>
        </div>
        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" value="0">
        </div>
        <div class="form-group">
            <label>Marca</label>
            <input type="text" name="marca" class="form-control">
        </div>
        <div class="form-group">
            <label>Talla</label>
            <input type="text" name="talla" class="form-control">
        </div>
        <div class="form-group">
            <label>Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control">
        </div>
        <button type="submit" name="guardar" class="btn btn-danger">Guardar</button>
    </form>

    <hr>

    <h2>Listado de Productos</h2>
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Imagen</th>
            <th>Stock</th>
            <th>Marca</th>
            <th>Talla</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($prod = $productos_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $prod['IDproductos']; ?></td>
                <td><?php echo $prod['producto']; ?></td>
                <td><?php echo $prod['nombre_categoria']; ?></td>
                <td>
                    <?php if (!empty($prod['imagen'])): ?>
                        <img src="<?php echo $prod['imagen']; ?>" alt="Imagen" width="80">
                    <?php endif; ?>
                </td>
                <td><?php echo $prod['stock']; ?></td>
                <td><?php echo $prod['marca']; ?></td>
                <td><?php echo $prod['talla']; ?></td>
                <td>$<?php echo number_format($prod['precio'], 2); ?></td>
                <td>
                    <!-- Botón Editar abre un modal -->
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editar<?php echo $prod['IDproductos']; ?>">Editar</button>
                    <a href="productos.php?eliminar=<?php echo $prod['IDproductos']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                </td>
            </tr>

            <!-- Modal para Editar -->
            <div class="modal fade" id="editar<?php echo $prod['IDproductos']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Producto</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?php echo $prod['IDproductos']; ?>">
                                <div class="form-group">
                                    <label>Producto</label>
                                    <input type="text" name="producto" class="form-control" value="<?php echo $prod['producto']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Categoría</label>
                                    <select name="categorias" class="form-control" required>
                                        <?php
                                        $cats = $mysqli->query("SELECT IDcategorias, categoria FROM categorias");
                                        while ($c = $cats->fetch_assoc()): ?>
                                            <option value="<?php echo $c['IDcategorias']; ?>" <?php if ($c['IDcategorias']==$prod['categorias']) echo "selected"; ?>>
                                                <?php echo $c['categoria']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Imagen (subir nueva si deseas cambiarla)</label>
                                    <input type="file" name="imagen" class="form-control-file">
                                    <br>
                                    <?php if (!empty($prod['imagen'])): ?>
                                        <img src="<?php echo $prod['imagen']; ?>" width="100">
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input type="number" name="stock" class="form-control" value="<?php echo $prod['stock']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Marca</label>
                                    <input type="text" name="marca" class="form-control" value="<?php echo $prod['marca']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Talla</label>
                                    <input type="text" name="talla" class="form-control" value="<?php echo $prod['talla']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $prod['precio']; ?>">
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

<!-- Manda a llamar la parte inferior del codigo -->
<?php
require 'vista/parte_inferior.php';
?>           