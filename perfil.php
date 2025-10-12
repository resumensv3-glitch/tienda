<?php
// Manda a llamar la parte superior de la página
require 'vista/parte_superior_administrador.php';
require 'conexion.php';
// session_start(); // 🔸 Ya no es necesario, se ejecuta en parte_superior_administrador.php

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['IDusuarios'])) {
    header("Location: login.php");
    exit;
}

// Obtener datos del usuario logueado
$id = $_SESSION['IDusuarios'];
$query = $mysqli->query("SELECT * FROM usuarios WHERE IDusuarios = '$id'");
$usuario = $query->fetch_assoc();

// Si no tiene foto, asignar una por defecto
if (empty($usuario['foto'])) {
    $usuario['foto'] = 'uploads/perfil_default.png';
}

// Si se actualiza la foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $nombreFoto = uniqid() . "_" . basename($_FILES['foto']['name']);
    $rutaDestino = $uploadDir . $nombreFoto;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
        $mysqli->query("UPDATE usuarios SET foto = '$rutaDestino' WHERE IDusuarios = '$id'");
        $usuario['foto'] = $rutaDestino;
        $_SESSION['foto'] = $rutaDestino;
    }
}

// Consultar clientes (lo que ya tenía tu código)
$result = $mysqli->query("SELECT * FROM Clientes ORDER BY IDcliente DESC");
?>

<!-- Muestra el contenido principal de la página -->
<div class="container-fluid">

    
    <!-- ✅ CONTENIDO NUEVO: PERFIL DEL USUARIO -->
    <div class="card shadow mb-4" style="max-width: 800px; margin:auto;">
        <div class="card-header py-3 bg-danger text-white">
            <h5 class="m-0 font-weight-bold">Perfil de <?php echo htmlspecialchars($usuario['rol']); ?></h5>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Columna izquierda: Foto -->
                <div class="col-md-4 text-center">
                    <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" 
                         class="rounded-circle mb-3" width="150" height="150" 
                         style="object-fit: cover; border: 3px solid #ccc;">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="foto" class="form-control mb-2" accept="image/*">
                        <button class="btn btn-sm btn-primary" type="submit">Actualizar foto</button>
                    </form>
                </div>

                <!-- Columna derecha: Datos -->
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nombre:</th>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        </tr>
                        <tr>
                            <th>Usuario:</th>
                            <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                        </tr>
                        <tr>
                            <th>Contraseña:</th>
                            <td>
                                <!-- 🔒 Contraseña oculta con puntitos -->
                                <input type="password" class="form-control" 
                                       value="<?php echo htmlspecialchars($usuario['contraseña']); ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Rol:</th>
                            <td>
                                <span class="badge badge-<?php 
                                    echo ($usuario['rol'] == 'admin') ? 'danger' : 
                                         (($usuario['rol'] == 'bodega') ? 'warning' : 'info'); ?>">
                                    <?php echo htmlspecialchars($usuario['rol']); ?>
                                </span>
                            </td>
                        </tr>
                    </table>

                    <!-- Mensaje según el rol -->
                    <?php if ($usuario['rol'] == 'admin'): ?>
                        <div class="alert alert-danger">
                            <strong>Administrador:</strong> Puede gestionar usuarios, productos y ventas.
                        </div>

                    <?php elseif ($usuario['rol'] == 'vendedor'): ?>
                        <div class="alert alert-info">
                            <strong>Vendedor:</strong> Puede registrar ventas y atender clientes.
                        </div>

                    <?php elseif ($usuario['rol'] == 'bodega'): ?>
                        <div class="alert alert-warning">
                            <strong>Bodeguero:</strong> Controla el inventario y movimientos de productos.
                        </div>

                    <?php else: ?>
                        <div class="alert alert-secondary">
                            <strong>Rol desconocido:</strong> Sin permisos asignados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- ✅ FIN DEL PERFIL -->
      
</div>
<!-- End of Main Content -->

<?php
// Manda a llamar la parte inferior del código
require 'vista/parte_inferior.php';
?>
