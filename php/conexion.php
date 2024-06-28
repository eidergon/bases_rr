<?php
// $servername = "10.206.69.138:11059";
// $username = "eider_dev";
// $password = "65KehoBEb6t3";
// $database = "base_rr";

$servername = "localhost";
$username = "root";
$password = "";
$database = "base_rr";

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
} else {
}
