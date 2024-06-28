<?php
// Conexión a la base de datos
// $servername = "localhost";
// $username = "root";
// $password = "";
// $database = "base_rr";

$servername = "10.206.69.138:11059";
$username = "eider_dev";
$password = "65KehoBEb6t3";
$database = "base_rr";

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $columnas = isset($_POST['columnas']) ? $_POST['columnas'] : [];

    // Inicializamos un array para almacenar las condiciones de la consulta
    $conditions = [];

    foreach ($columnas as $columna) {
        if ($columna == 'estado' && isset($_POST['estado'])) {
            $estado = $_POST['estado'];
            // Añadimos la condición para el estado
            $conditions[] = "estado = '$estado'";
        } elseif ($columna == 'ubicacion' && isset($_POST['ubicacion'])) {
            $ubicacion = $_POST['ubicacion'];
            // Añadimos la condición para la ubicación usando LIKE
            $conditions[] = "ubicacion LIKE '%$ubicacion%'";
        }  elseif ($columna == 'cant_servicios' && isset($_POST['cant_servicios'])) {
            $cant_servicios = $_POST['cant_servicios'];
            // Añadimos la condición para la cantidad de servicios
            $conditions[] = "cant_servicios = $cant_servicios";
        } elseif ($columna == 'valor2' && isset($_POST['valor_min']) && isset($_POST['valor_max'])) {
            $valor_min = $_POST['valor_min'];
            $valor_max = $_POST['valor_max'];
            // Añadimos la condición para el rango de valor
            $conditions[] = "valor2 BETWEEN $valor_min AND $valor_max";
        } elseif ($columna == 'fecha_actualizacion' && isset($_POST['fecha_actualizacion'])) {
            $fecha_actualizacion = $_POST['fecha_actualizacion'];
            // Añadimos la condición para la fecha de actualización
            $conditions[] = "fecha_actualizacion LIKE '%$fecha_actualizacion%'";
        }
    }

    // Construimos la consulta SQL solo si hay condiciones
    if (!empty($conditions)) {
        $sql = "SELECT * FROM cuentas_detalle WHERE " . implode(" AND ", $conditions);
        
        $result = $conn->query($sql);

        echo $sql;
        if ($result->num_rows > 0) {
            echo "<table border='1'><tr>";
            echo "<th>Cuenta</th>";
            echo "<th>Numero</th>";
            echo "<th>Estado</th>";
            echo "<th>Nombre</th>";
            echo "<th>Ubicacion</th>";
            echo "<th>Servicios</th>";
            echo "<th>Cantidad de servicios</th>";
            echo "<th>Cedula</th>";
            echo "<th>Valor 1</th>";
            echo "<th>Valor 2</th>";
            echo "<th>Fecha de creacion</th>";
            echo "<th>Fecha de actualizacion</th>";
            // foreach ($columnas as $columna) {
            // }
            echo "</tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                    echo "<td>" . $row['cuenta'] . "</td>";
                    echo "<td>" . $row['numero'] . "</td>";
                    echo "<td>" . $row['estado'] . "</td>";
                    echo "<td>" . $row['nombre'] . "</td>";
                    echo "<td>" . $row['ubicacion'] . "</td>";
                    echo "<td>" . $row['servicios'] . "</td>";
                    echo "<td>" . $row['cant_servicios'] . "</td>";
                    echo "<td>" . $row['cedula'] . "</td>";
                    echo "<td>" . $row['valor1'] . "</td>";
                    echo "<td>" . $row['valor2'] . "</td>";
                    echo "<td>" . $row['fecha_creacion'] . "</td>";
                    echo "<td>" . $row['fecha_actualizacion'] . "</td>";
                // foreach ($columnas as $columna) {
                //     echo "<td>" . $row[$columna] . "</td>";
                // }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "0 resultados";
        }
    } else {
        echo "No se seleccionaron filtros.";
    }
}

$conn->close();
?>