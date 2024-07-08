<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $precio1 = $_POST["precio1"];
    $precio2 = $_POST["precio2"];
    $precio3 = $_POST["precio3"];

    $columna1 = '0-' . number_format($precio1 / 1000, 0) . 'k';
    $columna2 = number_format($precio1 / 1000, 0) . 'k-' . number_format($precio2 / 1000, 0) . 'k';
    $columna3 = '>' . number_format($precio3 / 1000, 0) . 'k';

    $sql2 = "SELECT categoria_servicio,
        COUNT(CASE WHEN valor1 BETWEEN 0 AND $precio1 - 1 THEN 1 END) AS '$columna1',
        COUNT(CASE WHEN valor1 BETWEEN $precio1 AND $precio2 - 1 THEN 1 END) AS '$columna2',
        COUNT(CASE WHEN valor1 >= $precio3 THEN 1 END) AS '$columna3'
        FROM historico_cuentas
            WHERE numero is not null
        GROUP BY categoria_servicio;";

    $result2 = $conn->query($sql2);
    $conn->close();
}
?>

<?php if ($result2->num_rows > 0) : ?>
    <thead>
        <tr>
            <th>Categoria de servicio</th>
            <th><?= $columna1 ?></th>
            <th><?= $columna2 ?></th>
            <th><?= $columna3 ?></th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php $grandTotal = 0; ?>
        <?php $grandTotal1 = 0; ?>
        <?php $grandTotal2 = 0; ?>
        <?php $grandTotal3 = 0; ?>
        <?php while ($row2 = $result2->fetch_assoc()) : ?>
            <?php $total = $row2["$columna1"] + $row2["$columna2"] + $row2["$columna3"]; ?>
            <?php $total1 = $row2["$columna1"]; ?>
            <?php $total2 = $row2["$columna2"]; ?>
            <?php $total3 = $row2["$columna3"]; ?>
            <?php $grandTotal += $total; ?>
            <?php $grandTotal1 += $total1; ?>
            <?php $grandTotal2 += $total2; ?>
            <?php $grandTotal3 += $total3; ?>
            <tr scope='row'>
                <td><?= $row2["categoria_servicio"]; ?></td>
                <td><?= number_format($row2["$columna1"]); ?></td>
                <td><?= number_format($row2["$columna2"]); ?></td>
                <td><?= number_format($row2["$columna3"]); ?></td>
                <td><?= number_format($total); ?></td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <td>Total</td>
            <td><?= number_format($grandTotal1) ?></td>
            <td><?= number_format($grandTotal2) ?></td>
            <td><?= number_format($grandTotal3) ?></td>
            <td><?= number_format($grandTotal) ?></td>
        </tr>
    </tbody>
<?php endif; ?>