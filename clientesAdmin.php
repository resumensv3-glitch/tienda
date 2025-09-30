<?php
// Manda a llamar la parte superior de la página
require 'vista/parte_superior_administrador.php';

// Consultar clientes
$result = $mysqli->query("SELECT * FROM Clientes ORDER BY IDcliente DESC");
?>
<!-- Muestra el contenido principal de la página -->

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Ver Clientes</h1>

       <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre completo</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>DUI</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['IDcliente'] ?></td>
                <td><?= $row['nombre'] . " " . $row['apellido'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['telefono'] ?></td>
                <td><?= $row['DUI'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>
<!-- End of Main Content -->

<?php
// Manda a llamar la parte inferior del código
require 'vista/parte_inferior.php';
?>
