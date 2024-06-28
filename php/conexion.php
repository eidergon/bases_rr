<?php
// $servername = "10.206.173.188:3306";
// $username = "mysqldb";
// $password = "0n3C0nt4ct1nt3rn4c10n4l.06++";
// $database = "nomina";

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
