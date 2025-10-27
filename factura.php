<?php
session_start();
require 'conexion.php';
require 'lib/fpdf.php';

if (!isset($_GET['id'])) {
    die('Factura no encontrada');
}

$IDventas = (int)$_GET['id'];

// ===== Datos de la venta =====
$stmt = $mysqli->prepare("
    SELECT v.*, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido, c.dui, u.nombre AS vendedor_nombre
    FROM ventas v
    LEFT JOIN clientes c ON v.IDcliente = c.IDcliente
    LEFT JOIN usuarios u ON v.IDusuarios = u.IDusuarios
    WHERE v.IDventas=?
");
$stmt->bind_param("i", $IDventas);
$stmt->execute();
$venta = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$venta) die('Factura no encontrada');

// ===== Detalles de la venta =====
$detalles = $mysqli->query("
    SELECT d.*, p.producto 
    FROM detalle_ventas d 
    INNER JOIN productos p ON d.IDproductos=p.IDproductos 
    WHERE d.IDventas=$IDventas
");

if (!$detalles || $detalles->num_rows == 0) die('No se encontraron productos para esta venta');

// ===== Generar PDF =====
$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('img/logonegro.png',10,8,33);
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,10, strtolower($venta['tipo'])=="credito fiscal" ? 'CREDITO FISCAL' : 'FACTURA',0,1,'C');
$pdf->Ln(3);

// Encabezado
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,6, utf8_decode('Mi tienda software'),0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6, utf8_decode($venta['vendedor_nombre']),0,1,'C');
$pdf->Cell(0,6, utf8_decode('Dirección: Carretera Litoral de Zacatecoluca, La Paz Este, La Paz'),0,1,'C');
$pdf->Cell(0,6, utf8_decode('Giro: Comercial N.C.P - N.R.C: 294712-1 - NIT: 0306-071266-101-5'),0,1,'C');
$pdf->Cell(0,6, utf8_decode('Tel: 7034 0655 - Email: itca.edu.sv'),0,1,'C');
$pdf->Ln(5);

// Número de factura
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6, utf8_decode('FACTURA No. '.$venta['referencia']),0,1,'L');
$pdf->Ln(3);

// Datos cliente
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6, utf8_decode("Nombre: ".$venta['cliente_nombre']." ".$venta['cliente_apellido']),0,1);
$pdf->Cell(0,6, utf8_decode("DUI/NIT: ".$venta['dui']),0,1);
$pdf->Cell(0,6, utf8_decode("Fecha: ".$venta['fecha']),0,1);
$pdf->Ln(5);

// Tabla productos
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,8, utf8_decode('Cant.'),1);
$pdf->Cell(80,8, utf8_decode('Descripción'),1);
$pdf->Cell(30,8, utf8_decode('P. Unitario'),1);
$pdf->Cell(30,8, utf8_decode('Subtotal'),1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);
while($row = $detalles->fetch_assoc()){
    $pdf->Cell(20,8,$row['cantidad'],1);
    $pdf->Cell(80,8, utf8_decode($row['producto']),1);
    $pdf->Cell(30,8,number_format($row['precio_unitario'],2),1,0,'R');
    $pdf->Cell(30,8,number_format($row['subtotal'],2),1,0,'R');
    $pdf->Ln();
}

// Totales
$pdf->Ln(5);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6, utf8_decode("SUMAS: $".number_format($venta['subtotal'],2)),0,1,'R');
$pdf->Cell(0,6, utf8_decode("IVA (13%): $".number_format($venta['iva'],2)),0,1,'R');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8, utf8_decode("VENTA TOTAL: $".number_format($venta['total'],2)),0,1,'R');

// Firma
$pdf->Ln(15);
$pdf->SetFont('Arial','',10);
$pdf->Cell(95,6, utf8_decode('ENTREGADO POR: ___________________'),0,0,'L');
$pdf->Cell(95,6, utf8_decode('RECIBIDO POR: ___________________'),0,1,'R');

// Descargar PDF directamente
$pdf->Output('D','factura_'.$venta['referencia'].'.pdf');
exit;
?>
