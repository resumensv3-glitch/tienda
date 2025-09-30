<?php
include 'conexion.php'; // Conexión a la base de datos

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];       // coincide con tu tabla
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    // Validar que las contraseñas coincidan
    if ($password !== $repassword) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, usuario, rol, contraseña) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $usuario, $rol, $passwordHash);

        if ($stmt->execute()) {
            $mensaje = "Usuario registrado exitosamente.";
        } else {
            $mensaje = "Error al registrar usuario: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registrar usuarios</title>

    <!-- Custom fonts for this template-->
    <link href="/tienda/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/tienda/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">CREA UN USUARIO</h1>
                        </div>

                        <!-- Mensaje de éxito o error -->
                        <?php if($mensaje != ""): ?>
                            <div class="alert <?php echo strpos($mensaje,'exitosamente') !== false ? 'alert-success' : 'alert-danger'; ?>">
                                <?php echo $mensaje; ?>
                            </div>
                        <?php endif; ?>

                        <form class="user" method="POST">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" name="nombre" class="form-control form-control-user" placeholder="Nombre completo" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="usuario" class="form-control form-control-user" placeholder="Usuario" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <select name="rol" class="form-control form-control-user" required>
                                    <option value="">Selecciona rol de usuario</option>
                                    <option value="admin">Admin</option>
                                    <option value="vendedor">Vendedor</option>
                                    <option value="bodega">Bodega</option>
                                </select>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" name="password" class="form-control form-control-user" placeholder="Contraseña" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" name="repassword" class="form-control form-control-user" placeholder="Repetir contraseña" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Registrar usuario
                            </button>
                        </form>

                        <hr>
                        <div class="text-center">
                            <a class="small" href="login.php">Iniciar sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="index.php" class="btn btn-primary btn-user">
    Volver al inicio
</a>

<!-- Scripts -->
<script src="/tienda/vendor/jquery/jquery.min.js"></script>
<script src="/tienda/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/tienda/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="/tienda/js/sb-admin-2.min.js"></script>

</body>
</html>
