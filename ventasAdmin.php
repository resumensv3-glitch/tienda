<?php
require 'vista/parte_superior_administrador.php';
require 'conexion.php';

// Consulta resumen por producto
$sql = "SELECT p.IDproductos, p.producto, p.precio, 
               SUM(d.cantidad) AS cantidad_vendida,
               SUM(d.subtotal) AS total_ventas
        FROM detalle_ventas d
        LEFT JOIN productos p ON d.IDproductos = p.IDproductos
        GROUP BY p.IDproductos
        ORDER BY total_ventas DESC";

$result = $mysqli->query($sql);

// Inicializar totales generales
$total_cantidad = 0;
$total_ventas = 0;
?>

<!-- Inicio del contenido de la página -->
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Resumen de productos vendidos</h1>

    <!-- Tabla de productos vendidos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Listado de productos vendidos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Producto</th>
                            <th>Producto</th>
                            <th>Precio unitario</th>
                            <th>Cantidad vendida</th>
                            <th>Total de ventas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()):
                                $total_cantidad += $row['cantidad_vendida'];
                                $total_ventas += $row['total_ventas'];
                            ?>
                                <tr>
                                    <td><?php echo $row['IDproductos']; ?></td>
                                    <td><?php echo $row['producto']; ?></td>
                                    <td>$<?php echo number_format($row['precio'],2); ?></td>
                                    <td><?php echo $row['cantidad_vendida']; ?></td>
                                    <td>$<?php echo number_format($row['total_ventas'],2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No se han vendido productos aún</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Totales Generales:</th>
                            <th><?php echo $total_cantidad; ?></th>
                            <th>$<?php echo number_format($total_ventas,2); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.fin del contenido de la página -->

<?php require 'vista/parte_inferior.php'; ?>
