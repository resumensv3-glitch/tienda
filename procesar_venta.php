<?php
session_start();
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['carrito'])) {
    // ===== Datos del cliente =====
    $nombre   = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email    = !empty($_POST['email']) ? $_POST['email'] : NULL;
    $telefono = !empty($_POST['telefono']) ? $_POST['telefono'] : NULL;
    $dui      = !empty($_POST['dui']) ? $_POST['dui'] : NULL;
    $tipo_factura = $_POST['tipo_factura'];

    // ===== Insertar cliente =====
    $stmt = $mysqli->prepare("INSERT INTO clientes (nombre, apellido, email, telefono, DUI) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $nombre, $apellido, $email, $telefono, $dui);
    $stmt->execute();
    $IDcliente = $stmt->insert_id;
    $stmt->close();

    // ===== Calcular totales =====
    $subtotal = 0;
    foreach ($_SESSION['carrito'] as $item) {
        $subtotal += $item['precio'] * $item['cantidad'];
    }
    $iva = $subtotal * 0.13;
    $total = $subtotal + $iva;

    // ===== Datos de la venta =====
    $referencia = "V-" . time();
    $IDusuarios = $_SESSION['IDusuarios'] ?? 1;

    // ===== Insertar venta =====
    $stmt = $mysqli->prepare("INSERT INTO ventas (referencia, IDusuarios, tipo, subtotal, iva, total, IDcliente) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sisdddi", $referencia, $IDusuarios, $tipo_factura, $subtotal, $iva, $total, $IDcliente);
    $stmt->execute();
    $IDventas = $stmt->insert_id;
    $stmt->close();

    // ===== Insertar detalle de venta y descontar stock =====
    foreach ($_SESSION['carrito'] as $item) {
        $subtotal_item = $item['precio'] * $item['cantidad'];

        // Detalle de venta
        $stmt = $mysqli->prepare("INSERT INTO detalle_ventas (IDventas, IDproductos, cantidad, precio_unitario, subtotal) VALUES (?,?,?,?,?)");
        $stmt->bind_param("iiidd", $IDventas, $item['IDproductos'], $item['cantidad'], $item['precio'], $subtotal_item);
        $stmt->execute();
        $stmt->close();

        // Actualizar stock
        $stmt = $mysqli->prepare("UPDATE productos SET stock = stock - ? WHERE IDproductos=?");
        $stmt->bind_param("ii", $item['cantidad'], $item['IDproductos']);
        $stmt->execute();
        $stmt->close();
    }

    // ===== Vaciar carrito =====
    unset($_SESSION['carrito']);

    // ===== Redirigir a la factura =====
    header("Location: factura.php?id=$IDventas");
    exit;
}
?>
