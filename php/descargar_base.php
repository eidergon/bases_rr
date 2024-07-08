<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tabla = isset($_POST['tabla']) ? $_POST['tabla'] : '';
    $columnas = isset($_POST['columnas']) ? $_POST['columnas'] : [];

    // Inicializamos un array para almacenar las condiciones de la consulta
    $conditions = [];

    if ($tabla == 'historico_cuentas') {
        foreach ($columnas as $columna) {
            if ($columna == 'estado' && isset($_POST['estado'])) {
                $estado = $_POST['estado'];
                $conditions[] = "estado = '$estado'";
            } elseif ($columna == 'zona' && isset($_POST['zona'])) {
                $zona = $_POST['zona'];
                $conditions[] = "zona = '$zona'";
            } elseif ($columna == 'cant_servicios' && isset($_POST['cant_servicios'])) {
                $cant_servicios = $_POST['cant_servicios'];
                $conditions[] = "cant_servicios = $cant_servicios";
            } elseif ($columna == 'valor1') {
                if (isset($_POST['operacion'])) {
                    $operacion = $_POST['operacion'];
                    if ($operacion == 'menor_que' && isset($_POST['valor'])) {
                        $valor = $_POST['valor'];
                        $conditions[] = "valor1 <= $valor";
                    } elseif ($operacion == 'mayor_que' && isset($_POST['valor'])) {
                        $valor = $_POST['valor'];
                        $conditions[] = "valor1 >= $valor";
                    } elseif ($operacion == 'entre' && isset($_POST['valor_min']) && isset($_POST['valor_max'])) {
                        $valor_min = $_POST['valor_min'];
                        $valor_max = $_POST['valor_max'];
                        $conditions[] = "valor1 BETWEEN $valor_min AND $valor_max";
                    }
                }
            } elseif ($columna == 'fecha_actualizacion' && isset($_POST['fecha_actualizacion'])) {
                $fecha_actualizacion = $_POST['fecha_actualizacion'];
                $conditions[] = "fecha_actualizacion = '$fecha_actualizacion'";
            } elseif ($columna == 'tipo_servicios' && isset($_POST['tipo_servicios'])) {
                $tipo_servicios = $_POST['tipo_servicios'];
                $conditions[] = "tipo_servicios = '$tipo_servicios'";
            } elseif ($columna == 'categoria_servicio' && isset($_POST['categoria_servicio'])) {
                $categoria_servicio = $_POST['categoria_servicio'];
                $conditions[] = "categoria_servicio = '$categoria_servicio'";
            }
        }
        $conditions[] = "numero IS NOT NULL";
    } elseif ($tabla == 'numeros_detalle') {
        foreach ($columnas as $columna) {
            if ($columna == 'hogar' && isset($_POST['hogar'])) {
                $hogar = $_POST['hogar'];
                $conditions[] = "hogar = '$hogar'";
            } elseif ($columna == 'estado2' && isset($_POST['estado'])) {
                $estado = $_POST['estado'];
                $conditions[] = "estado = '$estado'";
            } elseif ($columna == 'fecha_actualizacion' && isset($_POST['fecha_actualizacion'])) {
                $fecha_actualizacion = $_POST['fecha_actualizacion'];
                $conditions[] = "fecha_actualizacion = '$fecha_actualizacion'";
            }
        }
    }
}

// Construimos la consulta SQL solo si hay condiciones y se seleccionó una tabla
if (!empty($conditions) && !empty($tabla)) {
    $sql = "SELECT * FROM $tabla WHERE " . implode(" AND ", $conditions) . " limit 100";
    $sql2 = "SELECT * FROM $tabla WHERE " . implode(" AND ", $conditions);

    $result = $conn->query($sql);
} else {
    echo "No se seleccionaron filtros o no se determinó la tabla.";
}

$conn->close();
?>

<?php if ($tabla == 'historico_cuentas') : ?>
    <!-- tabla de cuentas -->
    <?php if ($result->num_rows > 0) : ?>
        <form action="php/excel.php" method="post">
            <input type="hidden" value="<?= $sql2 ?>" name="sql">
            <input type="hidden" value="<?= $tabla ?>" name="tabla">
            <button class="buttonDownload">Download</button>
        </form>
        <table class="table" id="table">
            <thead>
                <tr>
                    <th>Cuenta</th>
                    <th>Numero</th>
                    <th>Estado</th>
                    <th>Nombre</th>
                    <th>Ubicacion</th>
                    <th>Zona</th>
                    <th>Servicios</th>
                    <th>tipo_servicios</th>
                    <th>Internet</th>
                    <th>TV</th>
                    <th>Voz</th>
                    <th>Cedula</th>
                    <th>Valor 1</th>
                    <th>Valor 2</th>
                    <th>Fecha de creacion</th>
                    <th>Fecha de actualizacion</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr scope='row'>
                        <td><?= $row["cuenta"]; ?></td>
                        <td><?= $row["numero"]; ?></td>
                        <td><?= $row["estado"]; ?></td>
                        <td><?= $row["nombre"]; ?></td>
                        <td><?= $row["ubicacion"]; ?></td>
                        <td><?= $row["zona"]; ?></td>
                        <td><?= $row["servicios"]; ?></td>
                        <td><?= $row["tipo_servicios"]; ?></td>
                        <td><?= $row["internet"]; ?></td>
                        <td><?= $row["tv"]; ?></td>
                        <td><?= $row["voz"]; ?></td>
                        <td><?= $row["cedula"]; ?></td>
                        <td><?= $row["valor1"]; ?></td>
                        <td><?= $row["valor1"]; ?></td>
                        <td><?= $row["fecha_creacion"]; ?></td>
                        <td><?= $row["fecha_actualizacion"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <table class="table" id="table">
            <thead>
                <tr>
                    <th>Cuenta</th>
                    <th>Numero</th>
                    <th>Estado</th>
                    <th>Nombre</th>
                    <th>zona</th>
                    <th>Servicios</th>
                    <th>Cantidad de servicios</th>
                    <th>Cedula</th>
                    <th>Valor 1</th>
                    <th>Valor 2</th>
                    <th>Fecha de creacion</th>
                    <th>Fecha de actualizacion</th>
                </tr>
            </thead>
            <tbody>
                <tr scope='row'>
                    <td colspan='12' class='no-data'>Sin Datos</td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
<?php else : ?>
    <!-- tabla de numeros -->
    <?php if ($result->num_rows > 0) : ?>
        <form action="php/excel.php" method="post">
            <input type="hidden" value="<?= $sql2 ?>" name="sql">
            <input type="hidden" value="<?= $tabla ?>" name="tabla">
            <button class="buttonDownload">Download</button>
        </form>
        <table class="table" id="table">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>Hogar</th>
                    <th>Estado</th>
                    <th>Fecha de creacion</th>
                    <th>Fecha de actualizacion</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr scope='row'>
                        <td><?= $row["numero"]; ?></td>
                        <td><?= $row["hogar"]; ?></td>
                        <td><?= $row["estado"]; ?></td>
                        <td><?= $row["fecha_creacion"]; ?></td>
                        <td><?= $row["fecha_actualizacion"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <table class="table" id="table">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>Hogar</th>
                    <th>Estado</th>
                    <th>Fecha de creacion</th>
                    <th>Fecha de actualizacion</th>
                </tr>
            </thead>
            <tbody>
                <tr scope='row'>
                    <td colspan='5' class='no-data'>Sin Datos</td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif; ?>