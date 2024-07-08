<?php
require_once '../php/conexion.php';

$sql = "SELECT tipo_servicios,
        COUNT(CASE WHEN zona='DENTRO' THEN 1 END) AS Dentro_zona,
        COUNT(CASE WHEN zona='FUERA' THEN 1 END) AS Fuera_zona,
        COUNT(CASE WHEN zona='SIN COBERTURA' THEN 1 END) AS No_cobertura
    FROM historico_cuentas
        WHERE numero is not null
    GROUP BY tipo_servicios";

$result = $conn->query($sql);

$sql2 = "SELECT categoria_servicio,
        COUNT(CASE WHEN valor1 BETWEEN 0 AND 29999 THEN 1 END) AS '0-30k',
        COUNT(CASE WHEN valor1 BETWEEN 30000 AND 89999 THEN 1 END) AS '30k-90k',
        COUNT(CASE WHEN valor1 >= 90000 THEN 1 END) AS '>90k'
    FROM historico_cuentas
        WHERE numero is not null
    GROUP BY categoria_servicio;";

$result2 = $conn->query($sql2);
$conn->close();
?>

<div class="container-vista">
    <?php if ($result->num_rows > 0) : ?>
        <div class="tipo-servicios">
            <h2>tipo de servicios</h2>
            <form id="cambiar-valor">
                <label class="label">
                    Operacion:
                    <select name="operacion" id="operacion" class="select" required>
                        <option value="">---------</option>
                        <option value="menor_que">Menor que</option>
                        <option value="mayor_que">Mayor que</option>
                        <option value="entre">Entre</option>
                    </select>
                </label>
                <div class="dynamic-inputs" id="dynamic-inputs"></div>
                <button class="boton">cambiar precios</button>
            </form>
            <table class="table" id="tabla-tipo-servicios">
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
            </table>
        </div>
    <?php endif; ?>

    <?php if ($result2->num_rows > 0) : ?>
        <div class="categoria-servicios">
            <h2>Categoria de servicios</h2>
            <form id="cambiar-precios">
                <input type="number" class="select" name="precio1" placeholder="precio 1" required>
                <input type="number" class="select" name="precio2" placeholder="precio 2" required>
                <input type="number" class="select" name="precio3" placeholder="precio 3" required>
                <button class="boton">cambiar precios</button>
            </form>
            <table class="table" id="table-cambiar-precios">
                <thead>
                    <tr>
                        <th>Categoria de servicio</th>
                        <th>0 - 30k</th>
                        <th>30k - 90k</th>
                        <th>Más de 90k</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $grandTotal = 0; ?>
                    <?php $grandTotal1 = 0; ?>
                    <?php $grandTotal2 = 0; ?>
                    <?php $grandTotal3 = 0; ?>
                    <?php while ($row2 = $result2->fetch_assoc()) : ?>
                        <?php $total = $row2["0-30k"] + $row2["30k-90k"] + $row2[">90k"]; ?>
                        <?php $total1 = $row2["0-30k"]; ?>
                        <?php $total2 = $row2["30k-90k"]; ?>
                        <?php $total3 = $row2[">90k"]; ?>
                        <?php $grandTotal += $total; ?>
                        <?php $grandTotal1 += $total1; ?>
                        <?php $grandTotal2 += $total2; ?>
                        <?php $grandTotal3 += $total3; ?>
                        <tr scope='row'>
                            <td><?= $row2["categoria_servicio"]; ?></td>
                            <td><?= number_format($row2["0-30k"]); ?></td>
                            <td><?= number_format($row2["30k-90k"]); ?></td>
                            <td><?= number_format($row2[">90k"]); ?></td>
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
            </table>
        </div>
    <?php endif; ?>
</div>