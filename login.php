<?php
session_start();
require 'conexion.php';

// Si ya hay sesión activa, redirigimos según su rol
if (isset($_SESSION['IDusuarios']) && isset($_SESSION['rol'])) {
    $rol = $_SESSION['rol'];
    if ($rol === 'admin') header("Location: indexAdmin.php");
    elseif ($rol === 'vendedor') header("Location: indexVende.php");
    elseif ($rol === 'bodega') header("Location: indexBode.php");
    else header("Location: index.php");
    exit;
}

$error = "";

// Procesar login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Prepared statement
    if ($stmt = $mysqli->prepare("SELECT IDusuarios, contraseña, rol FROM usuarios WHERE usuario = ?")) {
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($IDusuarios, $hash_password, $tipo);
            $stmt->fetch();

            $password_ok = false;

            // Verificar contraseña (segura con password_hash)
            if (password_verify($password, $hash_password)) {
                $password_ok = true;
            } else {
                // Compatibilidad temporal: contraseñas en texto plano
                if ($password === $hash_password) {
                    $password_ok = true;
                }
            }

            if ($password_ok) {
                // Guardar datos en sesión
                $_SESSION['IDusuarios'] = $IDusuarios;
                $_SESSION['rol'] = $tipo;

                // Redirigir según rol
                switch ($tipo) {
                    case 'admin':
                        header("Location: indexAdmin.php");
                        break;
                    case 'vendedor':
                        header("Location: indexVende.php");
                        break;
                    case 'bodega':
                        header("Location: indexBode.php");
                        break;
                    default:
                        header("Location: index.php");
                        break;
                }
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos";
            }
        } else {
            $error = "Usuario o contraseña incorrectos";
        }

        $stmt->close();
    } else {
        $error = "Error en el servidor.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Mi tienda - Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        /* Imagen lateral en el login */
        .bg-login-image {
            background: url('img/logonegro.png'); /* Cambia la ruta según tu proyecto */
            background-position: center;
            background-size:50%;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="bg-gradient-primary">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <!-- Imagen lateral -->
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>

                        <!-- Formulario -->
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">¡Bienvenido!</h1>
                                </div>

                                <?php if (!empty($error)) { ?>
                                    <div class="alert alert-danger"><?= $error ?></div>
                                <?php } ?>

                                <form class="user" method="POST" action="login.php">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user"
                                               name="usuario" placeholder="Ingresar usuario..." required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                               name="password" placeholder="Contraseña" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Iniciar sesión
                                    </button>
                                </form>

                                
                            </div>
                        </div>
                        <!-- Fin del formulario -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>
