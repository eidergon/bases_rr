<?php
require 'conexion.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = $_POST["sql"];
    $tabla = $_POST["tabla"];

    $consulta = mysqli_query($conn, "$sql");
    $docu = "consulta base {$tabla}.xls";

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=" . $docu);
    header("Pragma: no-cache");
    header('Expires: 0');
}
?>

<?php if ($tabla == 'historico_cuentas') : ?>
    <table border="1">
        <thead>
            <tr>
                <th>Cuenta</th>
                <th>Numero</th>
                <th>Estado</th>
                <th>Nombre</th>
                <th>Ubicacion</th>
                <th>Zona</th>
                <th>Servicios</th>
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
            <?php while ($row = $consulta->fetch_assoc()) : ?>
                <tr scope='row'>
                    <td><?= $row["cuenta"]; ?></td>
                    <td><?= $row["numero"]; ?></td>
                    <td><?= $row["estado"]; ?></td>
                    <td><?= $row["nombre"]; ?></td>
                    <td><?= $row["ubicacion"]; ?></td>
                    <td><?= $row["zona"]; ?></td>
                    <td><?= $row["servicios"]; ?></td>
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
    <table border="1">
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
                <?php while ($row = $consulta->fetch_assoc()) : ?>
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
<?php endif; ?>