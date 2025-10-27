<?php
require 'conexion.php';
require 'vista/parte_superior_bodega.php';

$mensaje = "";
$tipo_mensaje = "success";

// --- Obtener categorías solo una vez ---
$categorias_result = $mysqli->query("SELECT IDcategorias, categoria FROM categorias");
$categorias = [];
while ($row = $categorias_result->fetch_assoc()) {
    $categorias[$row['IDcategorias']] = $row['categoria'];
}

// --- Eliminar producto ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $res = $mysqli->query("SELECT imagen FROM productos WHERE IDproductos = $id");
    if ($res && $fila = $res->fetch_assoc()) {
        if (!empty($fila['imagen']) && file_exists($fila['imagen'])) unlink($fila['imagen']);
    }
    if ($mysqli->query("DELETE FROM productos WHERE IDproductos = $id")) {
        $mensaje = "Producto eliminado correctamente.";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al eliminar producto.";
        $tipo_mensaje = "danger";
    }
}

// --- Insertar nuevo producto ---
if (isset($_POST['guardar'])) {
    $producto      = $_POST['producto'];
    $categorias_id = $_POST['categorias'];
    $stock         = $_POST['stock'];
    $marca         = $_POST['marca'];
    $talla         = $_POST['talla'];
    $precio        = $_POST['precio'];
    $rutaImagen    = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg','image/png','image/gif'];
        if (in_array($_FILES['imagen']['type'], $allowed)) {
            $nombreArchivo = $_FILES['imagen']['name'];
            $tmpName       = $_FILES['imagen']['tmp_name'];
            $carpetaDestino = "uploads/";
            if (!is_dir($carpetaDestino)) mkdir($carpetaDestino, 0777, true);
            $rutaImagen = $carpetaDestino . time() . "_" . basename($nombreArchivo);
            if (!move_uploaded_file($tmpName, $rutaImagen)) {
                $mensaje = "Error al mover la imagen. Verifica permisos.";
                $tipo_mensaje = "danger";
                $rutaImagen = null;
            }
        } else {
            $mensaje = "Formato de imagen no permitido.";
            $tipo_mensaje = "danger";
            $rutaImagen = null;
        }
    }

    $sql = "INSERT INTO productos (producto, categorias, imagen, stock, marca, talla, precio)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sisissd", $producto, $categorias_id, $rutaImagen, $stock, $marca, $talla, $precio);
    if ($stmt->execute()) {
        $mensaje = "Producto agregado correctamente.";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al guardar en BD: " . $stmt->error;
        $tipo_mensaje = "danger";
    }
}

// --- Editar producto ---
if (isset($_POST['editar'])) {
    $id            = $_POST['id'];
    $producto      = $_POST['producto'];
    $categorias_id = $_POST['categorias'];
    $stock         = $_POST['stock'];
    $marca         = $_POST['marca'];
    $talla         = $_POST['talla'];
    $precio        = $_POST['precio'];

    $rutaImagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg','image/png','image/gif'];
        if (in_array($_FILES['imagen']['type'], $allowed)) {
            $nombreArchivo = $_FILES['imagen']['name'];
            $tmpName       = $_FILES['imagen']['tmp_name'];
            $carpetaDestino = "uploads/";
            if (!is_dir($carpetaDestino)) mkdir($carpetaDestino, 0777, true);
            $rutaImagen = $carpetaDestino . time() . "_" . basename($nombreArchivo);
            if (move_uploaded_file($tmpName, $rutaImagen)) {
                // Borrar imagen antigua
                $res = $mysqli->query("SELECT imagen FROM productos WHERE IDproductos = $id");
                if ($res && $fila = $res->fetch_assoc()) {
                    if (!empty($fila['imagen']) && file_exists($fila['imagen'])) unlink($fila['imagen']);
                }
            } else {
                $mensaje = "Error al mover la imagen.";
                $tipo_mensaje = "danger";
                $rutaImagen = null;
            }
        } else {
            $mensaje = "Formato de imagen no permitido.";
            $tipo_mensaje = "danger";
            $rutaImagen = null;
        }
    }

    $sql = "UPDATE productos SET producto=?, categorias=?, stock=?, marca=?, talla=?, precio=?"
         . ($rutaImagen ? ", imagen=?" : "")
         . " WHERE IDproductos=?";
    $stmt = $mysqli->prepare($sql);
    if ($rutaImagen) {
        $stmt->bind_param("siissdsi", $producto, $categorias_id, $stock, $marca, $talla, $precio, $rutaImagen, $id);
    } else {
        $stmt->bind_param("siissdi", $producto, $categorias_id, $stock, $marca, $talla, $precio, $id);
    }

    if ($stmt->execute()) {
        $mensaje = "Producto editado correctamente.";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al actualizar: " . $stmt->error;
        $tipo_mensaje = "danger";
    }
}

// --- Obtener productos ---
$productos_result = $mysqli->query("SELECT p.*, c.categoria AS nombre_categoria 
                                   FROM productos p 
                                   LEFT JOIN categorias c ON p.categorias = c.IDcategorias");
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">PRODUCTOS</h1>
    <div class="container mt-4">
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <!-- Formulario agregar -->
        <h2>Agregar Producto</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="producto" class="form-control mb-2" placeholder="Producto" required>
            <select name="categorias" class="form-control mb-2" required>
                <option value="">-- Selecciona una categoría --</option>
                <?php foreach($categorias as $id => $cat): ?>
                    <option value="<?php echo $id; ?>"><?php echo $cat; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="file" name="imagen" class="form-control-file mb-2"> <!-- ya no es required -->
            <input type="number" name="stock" class="form-control mb-2" placeholder="Stock" value="0">
            <input type="text" name="marca" class="form-control mb-2" placeholder="Marca">
            <input type="text" name="talla" class="form-control mb-2" placeholder="Talla">
            <input type="number" step="0.01" name="precio" class="form-control mb-2" placeholder="Precio">
            <button type="submit" name="guardar" class="btn btn-danger">Guardar</button>
        </form>

        <hr>
        <h2>Listado de Productos</h2>
        <table class="table table-bordered">
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
                <?php while($prod = $productos_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $prod['IDproductos']; ?></td>
                    <td><?php echo $prod['producto']; ?></td>
                    <td><?php echo $prod['nombre_categoria']; ?></td>
                    <td><?php if($prod['imagen']): ?><img src="<?php echo $prod['imagen']; ?>" width="80"><?php endif; ?></td>
                    <td><?php echo $prod['stock']; ?></td>
                    <td><?php echo $prod['marca']; ?></td>
                    <td><?php echo $prod['talla']; ?></td>
                    <td>$<?php echo number_format($prod['precio'],2); ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editarProducto(<?php echo htmlspecialchars(json_encode($prod)); ?>)">Editar</button>
                        <a href="productos.php?eliminar=<?php echo $prod['IDproductos']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal único para editar -->
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editar_id">
                    <input type="text" name="producto" id="editar_producto" class="form-control mb-2" required>
                    <select name="categorias" id="editar_categorias" class="form-control mb-2" required>
                        <?php foreach($categorias as $id => $cat): ?>
                            <option value="<?php echo $id; ?>"><?php echo $cat; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="file" name="imagen" class="form-control-file mb-2"> <!-- ya no es required -->
                    <img id="imagen_preview" width="100" class="mb-2"><br>
                    <input type="number" name="stock" id="editar_stock" class="form-control mb-2">
                    <input type="text" name="marca" id="editar_marca" class="form-control mb-2">
                    <input type="text" name="talla" id="editar_talla" class="form-control mb-2">
                    <input type="number" step="0.01" name="precio" id="editar_precio" class="form-control mb-2">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="editar" class="btn btn-success">Guardar cambios</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarProducto(producto) {
    document.getElementById('editar_id').value = producto.IDproductos;
    document.getElementById('editar_producto').value = producto.producto;
    document.getElementById('editar_categorias').value = producto.categorias;
    document.getElementById('editar_stock').value = producto.stock;
    document.getElementById('editar_marca').value = producto.marca;
    document.getElementById('editar_talla').value = producto.talla;
    document.getElementById('editar_precio').value = producto.precio;
    document.getElementById('imagen_preview').src = producto.imagen ? producto.imagen : '';
    $('#modalEditar').modal('show');
}
</script>

<?php
require 'vista/parte_inferior.php';
?>
