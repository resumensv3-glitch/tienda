<?php
session_start();
require 'conexion.php';

// ===== Eliminar producto del carrito =====
if (isset($_GET['eliminar']) && isset($_SESSION['carrito'])) {
    $id = $_GET['eliminar'];
    foreach ($_SESSION['carrito'] as $i => $item) {
        if ($item['IDproductos'] == $id) {
            unset($_SESSION['carrito'][$i]);
        }
    }
    header("Location: carrito.php"); // redirige antes de enviar HTML
    exit;
}

// ===== Verificar carrito vacío =====
if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    require 'vista/parte_superior_vendedor.php';
    echo "<div class='container-fluid'>
            <div class='alert alert-warning mt-4'>
                El carrito está vacío. <a href='VenderVenta.php' class='alert-link'>Seguir comprando</a>
            </div>
          </div>";
    require 'vista/parte_inferior.php';
    exit;
}

// ===== Calcular totales =====
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
$iva = $total * 0.13;
$gran_total = $total + $iva;

// ===== Parte superior HTML =====
require 'vista/parte_superior_vendedor.php';
?>

<!-- Inicio del contenido de la página -->
<div class="container-fluid">

    <!-- Encabezado -->
    <h1 class="h3 mb-2 text-gray-800">Carrito de compras</h1>

    <!-- Tabla de carrito -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Productos en el carrito</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['carrito'] as $item): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($item['imagen'])): ?>
                                        <img src="<?php echo $item['imagen']; ?>" alt="Imagen" width="80">
                                    <?php else: ?>
                                        <span class="text-muted">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $item['producto']; ?></td>
                                <td><?php echo $item['cantidad']; ?></td>
                                <td>$<?php echo number_format($item['precio'],2); ?></td>
                                <td>$<?php echo number_format($item['precio'] * $item['cantidad'],2); ?></td>
                                <td>
                                    <a href="carrito.php?eliminar=<?php echo $item['IDproductos']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Totales -->
            <div class="mt-4">
                <p><strong>Subtotal:</strong> $<?php echo number_format($total,2); ?></p>
                <p><strong>IVA (13%):</strong> $<?php echo number_format($iva,2); ?></p>
                <p><strong>Total:</strong> $<?php echo number_format($gran_total,2); ?></p>
            </div>

            <!-- Botones -->
            <div class="mt-4">
                <a href="VenderVenta.php" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> Seguir comprando
                </a>
                <a href="checkout.php" class="btn btn-success">
                    <i class="fas fa-credit-card"></i> Finalizar compra
                </a>
            </div>
        </div>
    </div>

</div>
<!-- /.fin del contenido de la página -->

<?php require 'vista/parte_inferior.php'; ?>
