<?php
require 'vista/parte_superior_administrador.php';
require 'conexion.php';

// Total de Productos
$sqlProductos = "SELECT COUNT(*) AS totalProductos FROM productos";
$resProductos = $mysqli->query($sqlProductos);
$rowProductos = $resProductos->fetch_assoc();
$totalProductos = $rowProductos['totalProductos'];

// Total de Categorías
$sqlCategorias = "SELECT COUNT(*) AS totalCategorias FROM categorias";
$resCategorias = $mysqli->query($sqlCategorias);
$rowCategorias = $resCategorias->fetch_assoc();
$totalCategorias = $rowCategorias['totalCategorias'];

// Total de ventas y total de IVA
$sqlVentas = "SELECT SUM(subtotal) AS total_subtotal, SUM(iva) AS total_iva, SUM(total) AS total_ventas FROM ventas";
$resVentas = $mysqli->query($sqlVentas);
$rowVentas = $resVentas->fetch_assoc();
$totalIVA = $rowVentas['total_iva'] ?? 0;
$totalVentas = $rowVentas['total_ventas'] ?? 0;

// Consultar categorías y productos para la gráfica
$sql = "SELECT c.categoria, COUNT(p.IDproductos) as total
        FROM categorias c
        LEFT JOIN productos p ON p.categorias = c.IDcategorias
        GROUP BY c.IDcategorias";
$result = $mysqli->query($sql);

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['categoria'];
    $data[] = $row['total'];
}

$labels_json = json_encode($labels);
$data_json = json_encode($data);

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generar reporte</a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Total de Productos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Productos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalProductos; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Categorías -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total de Categorías</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalCategorias; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- IVA Total -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">IVA Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($totalIVA,2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Ventas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total de Ventas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($totalVentas,2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row con gráficas -->
    <div class="row">

        <!-- Gráfica de barras -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger">Distribución de Productos por Categoría</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="graficaColumnas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica circular -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger">Productos por Categoría</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="graficaCircular"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /.container-fluid -->

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficaColumnas').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $labels_json; ?>,
            datasets: [{
                label: 'Cantidad de Productos',
                data: <?php echo $data_json; ?>,
                backgroundColor: [
                    '#e74a3b','#f6c23e','#4e73df','#FFD700','#1E90FF','#008000'
                ],
                borderColor: 'rgba(0,0,0,0.8)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    const ctxPie = document.getElementById('graficaCircular').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: <?php echo $labels_json; ?>,
            datasets: [{
                data: <?php echo $data_json; ?>,
                backgroundColor: [
                    '#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40'
                ],
                borderColor: '#FFFFFF',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'right' } }
        }
    });
</script>

<?php
require 'vista/parte_inferior.php';
?>
