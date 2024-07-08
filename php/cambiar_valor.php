<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor = $_POST['valor'];
    $operacion = $_POST['operacion'];

    if ($operacion == 'menor_que' && isset($_POST['valor'])) {
        $sql = "SELECT tipo_servicios,
                COUNT(CASE WHEN zona='DENTRO' THEN 1 END) AS Dentro_zona,
                COUNT(CASE WHEN zona='FUERA' THEN 1 END) AS Fuera_zona,
                COUNT(CASE WHEN zona='SIN COBERTURA' THEN 1 END) AS No_cobertura
            FROM historico_cuentas
                WHERE valor1 < $valor
            GROUP BY tipo_servicios";
    } elseif ($operacion == 'mayor_que' && isset($_POST['valor'])) {
        $sql = "SELECT tipo_servicios,
                COUNT(CASE WHEN zona='DENTRO' THEN 1 END) AS Dentro_zona,
                COUNT(CASE WHEN zona='FUERA' THEN 1 END) AS Fuera_zona,
                COUNT(CASE WHEN zona='SIN COBERTURA' THEN 1 END) AS No_cobertura
            FROM historico_cuentas
                WHERE valor1 > $valor
            GROUP BY tipo_servicios";
    } elseif ($operacion == 'entre' && isset($_POST['valor_min']) && isset($_POST['valor_max'])) {
        $valor_min = $_POST['valor_min'];
        $valor_max = $_POST['valor_max'];
        $sql = "SELECT tipo_servicios,
                COUNT(CASE WHEN zona='DENTRO' THEN 1 END) AS Dentro_zona,
                COUNT(CASE WHEN zona='FUERA' THEN 1 END) AS Fuera_zona,
                COUNT(CASE WHEN zona='SIN COBERTURA' THEN 1 END) AS No_cobertura
            FROM historico_cuentas
                WHERE valor1 BETWEEN $valor_min AND $valor_max
            GROUP BY tipo_servicios";
    }

    $result = $conn->query($sql);
    $conn->close();
}
?>

<?php if ($result->num_rows > 0) : ?>
    <thead>
        <tr>
            <th>Tipo de servicio</th>
            <th>Dentro de zona</th>
            <th>Fuera de zona</th>
            <th>No Cobertura</th>
            <th>Tatol</th>
        </tr>
    </thead>
    <tbody>
        <?php $grandTotal = 0; ?>
        <?php $grandTotaldz = 0; ?>
        <?php $grandTotalfz = 0; ?>
        <?php $grandTotalnc = 0; ?>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <?php $total = $row["Dentro_zona"] + $row["Fuera_zona"] + $row["No_cobertura"]; ?>
            <?php $totaldz = $row["Dentro_zona"] ?>
            <?php $totalfz = $row["Fuera_zona"] ?>
            <?php $totalnc = $row["No_cobertura"] ?>
            <?php $grandTotal += $total; ?>
            <?php $grandTotaldz += $totaldz; ?>
            <?php $grandTotalfz += $totalfz; ?>
            <?php $grandTotalnc += $totalnc; ?>
            <tr scope='row'>
                <td><?= $row["tipo_servicios"]; ?></td>
                <td><?= number_format($row["Dentro_zona"]); ?></td>
                <td><?= number_format($row["Fuera_zona"]); ?></td>
                <td><?= number_format($row["No_cobertura"]); ?></td>
                <td><?= number_format($total); ?></td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <td>Total</td>
            <td><?= number_format($grandTotaldz) ?></td>
            <td><?= number_format($grandTotalfz) ?></td>
            <td><?= number_format($grandTotalnc) ?></td>
            <td><?= number_format($grandTotal) ?></td>
        </tr>
    </tbody>
<?php endif; ?>