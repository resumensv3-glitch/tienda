<?php
session_start();
require 'vista/parte_superior_vendedor.php';

if (empty($_SESSION['carrito'])) {
    header("Location: VenderVenta.php");
    exit;
}
?>

<!-- Inicio del contenido de la página -->
<div class="container-fluid">

    <!-- Encabezado -->
    <h1 class="h3 mb-2 text-gray-800">Datos del Cliente</h1>

    <!-- Card del formulario -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Formulario de Cliente</h6>
        </div>
        <div class="card-body">
            <form method="post" action="procesar_venta.php">
                <div class="form-group">
                    <label for="nombre">Nombre *</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido *</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono">
                </div>

                <div class="form-group">
                    <label for="dui">DUI</label>
                    <input type="text" class="form-control" id="dui" name="dui">
                </div>

                <div class="form-group">
                    <label for="tipo_factura">Tipo de factura *</label>
                    <select class="form-control" id="tipo_factura" name="tipo_factura" required>
                        <option value="Consumidor Final">Consumidor Final</option>
                        <option value="Credito Fiscal">Crédito Fiscal</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-invoice-dollar"></i> Generar Factura
                    </button>
                    <a href="carrito.php" class="btn btn-warning">
                        <i class="fas fa-arrow-left"></i> Volver al carrito
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.fin del contenido de la página -->

<?php require 'vista/parte_inferior.php'; ?>
