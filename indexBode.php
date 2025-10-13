<?php
require 'vista/parte_superior_bodega.php';

$sqlProductos = "SELECT COUNT(*) AS totalProductos FROM productos";
$resProductos = $mysqli->query($sqlProductos);
$rowProductos = $resProductos->fetch_assoc();
$totalProductos = $rowProductos['totalProductos'];

// Total de categorías
$sqlCategorias = "SELECT COUNT(*) AS totalCategorias FROM categorias";
$resCategorias = $mysqli->query($sqlCategorias);
$rowCategorias = $resCategorias->fetch_assoc();
$totalCategorias = $rowCategorias['totalCategorias'];

// Consultar categorías y productos
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


                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                       <a href="reporte_productos.php" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" target="_blank">
    <i class="fas fa-download fa-sm text-white-50"></i> Generar reporte</a>

                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
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
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total de Categorias</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalCategorias; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Impuesto
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">IVA 13%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 13%" aria-valuenow="87" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Reportes</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Aqui comienza el apartado de las graficas -->
<div class="col-xl-8 col-lg-7">
    <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-danger">Distribucion de Productos por Categoría</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Opciones:</div>
                    <a class="dropdown-item" href="#">Actualizar</a>
                    <a class="dropdown-item" href="#">Exportar</a>
                </div>
            </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="chart-bar">
                <canvas id="graficaColumnas"></canvas>
            </div>
        </div>
    </div>
</div>

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
                    '#95b8f6',
                    '#f6c23e',
                    '#4e73df',
                    '#FFD700',
                    '#1E90FF',
                    '#008000'
                ],
                borderColor: 'rgba(0,0,0,0.8)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: ''
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
<!-- Aqui termina la grafica de barras-->


                       <!-- Pie Chart Card -->
<div class="col-xl-4 col-lg-5">
    <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-danger">Productos por Categoría</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Opciones:</div>
                    <a class="dropdown-item" href="#">Actualizar</a>
                    <a class="dropdown-item" href="#">Exportar</a>
                </div>
            </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="chart-pie pt-4 pb-2">
                <canvas id="graficaCircular"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script>
    const ctxPie = document.getElementById('graficaCircular').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie', // Circular
        data: {
            labels: <?php echo $labels_json; ?>,
            datasets: [{
                data: <?php echo $data_json; ?>,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ],
                borderColor: '#FFFFFF',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right' },
                
            }
        }
    });
</script>
                             
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">


                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
<?php
require 'vista/parte_inferior.php';
?>