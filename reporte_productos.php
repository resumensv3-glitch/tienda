<?php
require 'vendor/autoload.php';
require 'conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Configurar Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Consulta de productos con su categoría
$sql = "SELECT p.IDproductos, p.producto, c.categoria, p.imagen, p.stock, p.marca, p.talla, p.precio
        FROM productos p
        LEFT JOIN categorias c ON p.categorias = c.IDcategorias";
$resultado = $mysqli->query($sql);

// Encabezado del reporte
$html = '
<style>
body { font-family: Arial, sans-serif; font-size: 12px; }
h2 { text-align: center; color: #e74a3b; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
th { background-color: #e74a3b; color: white; }
img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
</style>

<h2>Reporte General de Productos</h2>
<p style="text-align:right;">Fecha: '.date("d/m/Y H:i").'</p>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Producto</th>
    <th>Categoría</th>
    <th>Stock</th>
    <th>Marca</th>
    <th>Talla</th>
    <th>Precio ($)</th>
</tr>
</thead>
<tbody>';

// Cuerpo de la tabla
if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        // Si la imagen está vacía, usar una por defecto
        $imagen = !empty($row['imagen']) ? 'img/productos/'.$row['imagen'] : 'img/default.png';
        $html .= '
        <tr>
            <td>'.$row['IDproductos'].'</td>
            <td>'.$row['producto'].'</td>
            <td>'.$row['categoria'].'</td>
            <td>'.$row['stock'].'</td>
            <td>'.$row['marca'].'</td>
            <td>'.$row['talla'].'</td>
            <td>'.number_format($row['precio'], 2).'</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="8">No hay productos registrados</td></tr>';
}

$html .= '</tbody></table>';

// Generar PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // horizontal para más columnas
$dompdf->render();

// Mostrar el PDF en el navegador
$dompdf->stream('reporte_productos.pdf', ['Attachment' => false]);
exit;
?>
