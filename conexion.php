<?php
// Iniciar sesión SIEMPRE al inicio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "tienda";

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}


?>
