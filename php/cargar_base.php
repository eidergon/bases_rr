<?php
require_once 'conexion.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_FILES['archivos']['type'];
    $tamanio = $_FILES['archivos']['size'];
    $archivotmp = $_FILES['archivos']['tmp_name'];
    $lineas = file($archivotmp);
    $base = $_POST['base'];

    $i = 0;
    $errors = 0;
    $insertedRecords = 0;
    $existente = 0;

    // Define el tamaño del lote
    $batchSize = 1000;
    $batchData = array();

    foreach ($lineas as $linea) {
        if ($i != 0) {
            $datos = explode(";", $linea);
            $cuentas = !empty($datos[0]) ? rtrim($datos[0]) : '';

            if ($base == 'Numeros') {
                // Comprueba si el numero ya existe
                $checkQuery = "SELECT numero FROM numeros_consultar WHERE numero = '$cuentas'";
                $checkResult = mysqli_query($conn, $checkQuery);
            } else {
                // Comprueba si la cuenta ya existe
                $checkQuery = "SELECT cuenta FROM cuentas_consultar WHERE cuenta = '$cuentas'";
                $checkResult = mysqli_query($conn, $checkQuery);
            }

            if (mysqli_num_rows($checkResult) == 0) {
                $batchData[] = "('$cuentas')";
            } else {
                $existente++;
            }

            // Inserta en la base de datos cuando el lote alcanza el tamaño definido
            if (count($batchData) == $batchSize) {
                if ($base == 'Numeros') {
                    $insertQuery = "INSERT INTO numeros_consultar (numero) VALUES " . implode(',', $batchData);
                } else {
                    $insertQuery = "INSERT INTO cuentas_consultar (cuenta) VALUES " . implode(',', $batchData);
                }
                
                if (mysqli_query($conn, $insertQuery)) {
                    $insertedRecords += $batchSize;
                } else {
                    $errors += $batchSize;
                }

                $batchData = array();
            }
        }
        $i++;
    }

    // Inserta cualquier dato restante que no alcanzó el tamaño del lote
    if (!empty($batchData)) {
        if ($base == 'Numeros') {
            $insertQuery = "INSERT INTO numeros_consultar (numero) VALUES " . implode(',', $batchData);
        } else {
            $insertQuery = "INSERT INTO cuentas_consultar (cuenta) VALUES " . implode(',', $batchData);
        }
        
        if (mysqli_query($conn, $insertQuery)) {
            $insertedRecords += count($batchData);
        } else {
            $errors += count($batchData);
        }
    }

    $response['status'] = $errors === 0 ? 'success' : 'error';
    $response['message'] = $errors === 0 ? 'Archivo subido correctamente.' : 'Error al subir archivo.';
    $response['total_records'] = count($lineas) - 1;
    $response['inserted_records'] = $insertedRecords;
    $response['duplicados'] = $existente;

    header('Content-Type: application/json');
    echo json_encode($response);
}
