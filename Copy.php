<?php
session_start();
require 'vista/parte_superior_vendedor.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar al carrito
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
        $stmt = $mysqli->prepare("SELECT producto, precio, stock FROM productos WHERE IDproductos=?");
        $stmt->bind_param("i", $IDproductos);
        $stmt->execute();
        $stmt->bind_result($producto, $precio, $stock);
        $stmt->fetch();
        $stmt->close();

        $_SESSION['carrito'][] = [
            'IDproductos' => $IDproductos,
            'producto' => $producto,
            'precio' => $precio,
            'cantidad' => $cantidad
        ];
    }

    header("Location: VenderVenta.php");
    exit;
}

// Traer productos
$result = $mysqli->query("SELECT * FROM productos WHERE stock > 0");
?>
<h1>Productos</h1>
<table border="1">
<tr>
    <th>Producto</th>
    <th>Precio</th>
    <th>Stock</th>
    <th>Acción</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?php echo $row['producto']; ?></td>
    <td><?php echo number_format($row['precio'],2); ?></td>
    <td><?php echo $row['stock']; ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="IDproductos" value="<?php echo $row['IDproductos']; ?>">
            <input type="number" name="cantidad" value="1" min="1" max="<?php echo $row['stock']; ?>" required>
            <button type="submit" name="agregar">Agregar al carrito</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>
<p><a href="carrito.php">Ver carrito (<?php echo count($_SESSION['carrito']); ?>)</a></p>


<?php
require 'vista/parte_inferior.php';
?>