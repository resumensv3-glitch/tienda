<?php
require 'conexion.php';
require 'lib/fpdf.php';

$IDventas = $_GET['id'] ?? 0;

// Obtener datos de la venta
$stmt = $mysqli->prepare("SELECT v.*, c.nombre, c.apellido, c.dui 
    FROM ventas v 
    LEFT JOIN clientes c ON v.IDcliente = c.IDcliente 
    WHERE v.IDventas=?");
$stmt->bind_param("i", $IDventas);
$stmt->execute();
$venta = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Obtener detalle de productos
$detalles = $mysqli->query("SELECT d.*, p.producto 
    FROM detalle_ventas d 
    INNER JOIN productos p ON d.IDproductos=p.IDproductos 
    WHERE d.IDventas=$IDventas");

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();


//Insertar logo
$pdf->Image('img/logonegro.png',10,8,33);


// Título dinámico
$pdf->SetFont('Arial','B',20);
if (strtolower($venta['tipo']) == "credito fiscal") {
    $pdf->Cell(0,10,'CREDITO FISCAL',0,1,'C');
} else {
    $pdf->Cell(0,10,'FACTURA',0,1,'C');
}
$pdf->Ln(3);


// --- ENCABEZADO --- //
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,6,'Mi tienda software',0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Marilyn Merlos Rosales',0,1,'C');
$pdf->Cell(0,6,'Direccion: Carretera Litoral de Zacatecoluca, La Paz Este, La Paz',0,1,'C');
$pdf->Cell(0,6,'Giro: Comercial N.C.P - N.R.C: 294712-1 - NIT: 0306-071266-101-5',0,1,'C');
$pdf->Cell(0,6,'Tel: 7034 0655 - Email: itca.edu.sv',0,1,'C');
$pdf->Ln(5);

// Número de factura
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6,'FACTURA No. '.$venta['referencia'],0,1,'L');
$pdf->Ln(3);

// --- DATOS CLIENTE --- //
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,"Nombre: ".$venta['nombre']." ".$venta['apellido'],0,1);
$pdf->Cell(0,6,"DUI/NIT: ".$venta['dui'],0,1);
$pdf->Cell(0,6,"Fecha: ".$venta['fecha'],0,1);
$pdf->Ln(5);

// --- TABLA PRODUCTOS --- //
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,8,'Cant.',1);
$pdf->Cell(80,8,'Descripcion',1);
$pdf->Cell(30,8,'P. Unitario',1);
$pdf->Cell(30,8,'Subtotal',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);
while($row = $detalles->fetch_assoc()){
    $pdf->Cell(20,8,$row['cantidad'],1);
    $pdf->Cell(80,8,$row['producto'],1);
    $pdf->Cell(30,8,number_format($row['precio_unitario'],2),1,0,'R');
    $pdf->Cell(30,8,number_format($row['subtotal'],2),1,0,'R');
    $pdf->Ln();
}

// --- TOTALES --- //
$pdf->Ln(5);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,"SUMAS: $".number_format($venta['subtotal'],2),0,1,'R');
$pdf->Cell(0,6,"IVA (13%): $".number_format($venta['iva'],2),0,1,'R');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,"VENTA TOTAL: $".number_format($venta['total'],2),0,1,'R');

// Firma
$pdf->Ln(15);
$pdf->SetFont('Arial','',10);
$pdf->Cell(95,6,'ENTREGADO POR: ___________________',0,0,'L');
$pdf->Cell(95,6,'RECIBIDO POR: ___________________',0,1,'R');

// Salida PDF
$pdf->Output("I","factura_".$venta['referencia'].".pdf");
?>
