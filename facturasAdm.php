<?php
require 'vista/parte_superior_administrador.php';
require 'conexion.php';

// Consulta resumen de ventas
$sql = "SELECT v.IDventas, v.tipo, v.subtotal, v.iva, v.total, 
               c.nombre, c.apellido
        FROM ventas v
        LEFT JOIN clientes c ON v.IDcliente = c.IDcliente
        ORDER BY v.IDventas DESC";

$result = $mysqli->query($sql);

// Inicializar totales generales
$total_subtotal = 0;
$total_iva = 0;
$total_total = 0;
?>
<!-- Inicio del contenido de la página -->
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Resumen de ventas</h1>

    <!-- Tabla de ventas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Listado de facturas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Venta</th>
                            <th>Cliente</th>
                            <th>Tipo Factura</th>
                            <th>Subtotal</th>
                            <th>IVA</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): 
                                $total_subtotal += $row['subtotal'];
                                $total_iva += $row['iva'];
                                $total_total += $row['total'];
                            ?>
                                <tr>
                                    <td><?php echo $row['IDventas']; ?></td>
                                    <td><?php echo $row['nombre'] . ' ' . $row['apellido']; ?></td>
                                    <td><?php echo $row['tipo']; ?></td>
                                    <td>$<?php echo number_format($row['subtotal'],2); ?></td>
                                    <td>$<?php echo number_format($row['iva'],2); ?></td>
                                    <td>$<?php echo number_format($row['total'],2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay ventas registradas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Totales Generales:</th>
                            <th>$<?php echo number_format($total_subtotal,2); ?></th>
                            <th>$<?php echo number_format($total_iva,2); ?></th>
                            <th>$<?php echo number_format($total_total,2); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.fin del contenido de la página -->

<?php require 'vista/parte_inferior.php'; ?>
