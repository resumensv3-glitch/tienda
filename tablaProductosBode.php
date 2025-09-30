<?php 
require 'conexion.php'; 
require 'vista/parte_superior_bodega.php';  

// Capturar filtros
$filtroCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$filtroPrecio = isset($_GET['precio']) ? $_GET['precio'] : '';

// Query base
$sql = "SELECT p.IDproductos, p.producto, c.categoria AS categoria, p.imagen,
               p.stock, p.marca, p.talla, p.precio 
        FROM productos p 
        LEFT JOIN categorias c ON p.categorias = c.IDcategorias
        WHERE 1=1";

// Aplicar filtros si existen
if (!empty($filtroCategoria)) {
    $sql .= " AND c.IDcategorias = " . intval($filtroCategoria);
}

if (!empty($filtroPrecio)) {
    if ($filtroPrecio == '1') {
        $sql .= " AND p.precio < 50";  // Menores de $50
    } elseif ($filtroPrecio == '2') {
        $sql .= " AND p.precio BETWEEN 50 AND 100"; // Entre $50 y $100
    } elseif ($filtroPrecio == '3') {
        $sql .= " AND p.precio > 100"; // Mayores de $100
    }
}

$sql .= " ORDER BY p.IDproductos DESC";
$result = $mysqli->query($sql);

// Obtener categorías para el select
$categorias = $mysqli->query("SELECT IDcategorias, categoria FROM categorias");
?> 

<!-- Inicio del contenido de la página -->
<div class="container-fluid">

    <!-- Encabezado -->
    <h1 class="h3 mb-2 text-gray-800">Tabla de productos</h1>

    <!-- Filtros -->
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <label>Categoría</label>
                <select name="categoria" class="form-control">
                    <option value="">-- Todas --</option>
                    <?php while ($cat = $categorias->fetch_assoc()) { ?>
                        <option value="<?= $cat['IDcategorias'] ?>" 
                            <?= ($filtroCategoria == $cat['IDcategorias']) ? 'selected' : '' ?>>
                            <?= $cat['categoria'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Precio</label>
                <select name="precio" class="form-control">
                    <option value="">-- Todos --</option>
                    <option value="1" <?= ($filtroPrecio == '1') ? 'selected' : '' ?>>Menores de $50</option>
                    <option value="2" <?= ($filtroPrecio == '2') ? 'selected' : '' ?>>Entre $50 y $100</option>
                    <option value="3" <?= ($filtroPrecio == '3') ? 'selected' : '' ?>>Mayores de $100</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-danger mr-2">Filtrar</button>
                <a href="tablaProductosBode.php" class="btn btn-warning">Limpiar</a>
            </div>
        </div>
    </form>

    <!-- Tabla -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Lista de productos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Imagen</th>
                            <th>Stock</th>
                            <th>Marca</th>
                            <th>Talla</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['IDproductos']}</td>
                                    <td>{$row['producto']}</td>
                                    <td>{$row['categoria']}</td>
                                    <td>";
                                        if (!empty($row['imagen'])) {
                                            echo "<img src='{$row['imagen']}' alt='Imagen' width='80'>";
                                        }
                                echo "</td>
                                    <td>{$row['stock']}</td>
                                    <td>{$row['marca']}</td>
                                    <td>{$row['talla']}</td>
                                    <td>\${$row['precio']}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No hay productos registrados</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.fin del contenido de la página -->

<?php require 'vista/parte_inferior.php'; ?>
