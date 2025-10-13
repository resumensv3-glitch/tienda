<?php
session_start();
require 'conexion.php';

// Inicializar carrito
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// ===== Agregar al carrito =====
if (isset($_POST['agregar'])) {
    $IDproductos = $_POST['IDproductos'];
    $cantidad = intval($_POST['cantidad']);

    // Verificar si ya existe en el carrito
    $existe = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['IDproductos'] == $IDproductos) {
            $item['cantidad'] += $cantidad;
            $existe = true;
            break;
        }
    }

    if (!$existe) {
        $stmt = $mysqli->prepare("SELECT producto, precio, stock, imagen FROM productos WHERE IDproductos=?");
        $stmt->bind_param("i", $IDproductos);
        $stmt->execute();
        $stmt->bind_result($producto, $precio, $stock, $imagen);
        $stmt->fetch();
        $stmt->close();

        $_SESSION['carrito'][] = [
            'IDproductos' => $IDproductos,
            'producto' => $producto,
            'precio' => $precio,
            'cantidad' => $cantidad,
            'imagen' => $imagen
        ];
    }

    header("Location: VenderVenta.php");
    exit;
}

// ===== Traer productos =====
$result = $mysqli->query("SELECT * FROM productos WHERE stock > 0");

// ===== Parte superior de la página =====
require 'vista/parte_superior_vendedor.php';
?>

<!-- Inicio del contenido de la página -->
<div class="container-fluid">

    <!-- Encabezado -->
    <h1 class="h3 mb-2 text-gray-800">Venta de productos</h1>

    <!-- Tabla con estilo -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Lista de productos disponibles</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($row['imagen'])): ?>
                                            <img src="<?php echo $row['imagen']; ?>" alt="Imagen" width="80">
                                        <?php else: ?>
                                            <span class="text-muted">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row['producto']; ?></td>
                                    <td>$<?php echo number_format($row['precio'],2); ?></td>
                                    <td><?php echo $row['stock']; ?></td>
                                    <td>
                                        <form method="post" class="d-flex">
                                            <input type="hidden" name="IDproductos" value="<?php echo $row['IDproductos']; ?>">
                                            <input type="number" name="cantidad" value="1" min="1" max="<?php echo $row['stock']; ?>" required class="form-control mr-2" style="width:80px;">
                                            <button type="submit" name="agregar" class="btn btn-sm btn-success">
                                                <i class="fas fa-cart-plus"></i> Agregar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No hay productos disponibles</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Link al carrito -->
    <p>
        <a href="carrito.php" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i> Ver carrito (<?php echo count($_SESSION['carrito']); ?>)
        </a>
    </p>

</div>
<!-- /.fin del contenido de la página -->

<?php require 'vista/parte_inferior.php'; ?>
