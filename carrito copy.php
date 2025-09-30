<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    echo "El carrito está vacío. <a href='VenderVenta.php'>Seguir comprando</a>";
    exit;
}

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    foreach ($_SESSION['carrito'] as $i => $item) {
        if ($item['IDproductos'] == $id) {
            unset($_SESSION['carrito'][$i]);
        }
    }
    header("Location: carrito.php");
    exit;
}

// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
$iva = $total * 0.13;
$gran_total = $total + $iva;
?>

<h1>Carrito de compras</h1>
<table border="1">
<tr>
    <th>Producto</th>
    <th>Cantidad</th>
    <th>Precio</th>
    <th>Subtotal</th>
    <th>Acción</th>
</tr>
<?php foreach ($_SESSION['carrito'] as $item): ?>
<tr>
    <td><?php echo $item['producto']; ?></td>
    <td><?php echo $item['cantidad']; ?></td>
    <td><?php echo number_format($item['precio'],2); ?></td>
    <td><?php echo number_format($item['precio']*$item['cantidad'],2); ?></td>
    <td><a href="carrito.php?eliminar=<?php echo $item['IDproductos']; ?>">Eliminar</a></td>
</tr>
<?php endforeach; ?>
</table>

<p>Subtotal: $<?php echo number_format($total,2); ?></p>
<p>IVA (13%): $<?php echo number_format($iva,2); ?></p>
<p>Total: $<?php echo number_format($gran_total,2); ?></p>

<p><a href="VenderVenta.php">Seguir comprando</a></p>
<p><a href="checkout.php">Finalizar compra</a></p>
