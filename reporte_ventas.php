<?php
require 'conexion.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Configuración del PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Consultar ventas
$sql = "SELECT v.IDventas, v.fecha, v.subtotal, v.iva, v.total, u.nombre AS usuario 
        FROM ventas v 
        LEFT JOIN usuarios u ON v.IDusuarios = u.IDusuarios 
        ORDER BY v.fecha DESC";
$result = $mysqli->query($sql);

// Construir tabla HTML
$html = '
<h2 style="text-align:center; color:#dc3545;">Reporte de Ventas</h2>
<p style="text-align:center;">Generado el ' . date("d/m/Y H:i") . '</p>
<table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse; font-size:12px;">
<thead style="background-color:#f8d7da;">
<tr>
    <th>ID</th>
    <th>Fecha</th>
    <th>Usuario</th>
    <th>Subtotal ($)</th>
    <th>IVA ($)</th>
    <th>Total ($)</th>
</tr>
</thead>
<tbody>';

$total_general = 0;
$total_iva = 0;
$total_subtotal = 0;

while ($row = $result->fetch_assoc()) {
    $html .= '
    <tr>
        <td style="text-align:center;">'.$row['IDventas'].'</td>
        <td>'.date("d/m/Y", strtotime($row['fecha'])).'</td>
        <td>'.htmlspecialchars($row['usuario'] ?? 'Desconocido').'</td>
        <td style="text-align:right;">'.number_format($row['subtotal'], 2).'</td>
        <td style="text-align:right;">'.number_format($row['iva'], 2).'</td>
        <td style="text-align:right;">'.number_format($row['total'], 2).'</td>
    </tr>';
    $total_subtotal += $row['subtotal'];
    $total_iva += $row['iva'];
    $total_general += $row['total'];
}

$html .= '
</tbody>
<tfoot>
<tr style="background-color:#f1b0b7; font-weight:bold;">
    <td colspan="3" style="text-align:right;">Totales:</td>
    <td style="text-align:right;">'.number_format($total_subtotal, 2).'</td>
    <td style="text-align:right;">'.number_format($total_iva, 2).'</td>
    <td style="text-align:right;">'.number_format($total_general, 2).'</td>
</tr>
</tfoot>
</table>
';

// Renderizar PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Descargar PDF
$dompdf->stream("reporte_ventas_" . date("Ymd_His") . ".pdf", ["Attachment" => true]);
exit;
?>
