<?php
ini_set('max_execution_time', 600); // Aumenta el tiempo máximo de ejecución a 600 segundos

require_once 'php/conexion.php';

// Array para almacenar la respuesta
$response = array();

// Verificar si se ha enviado una petición POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se ha subido un archivo
    if (isset($_FILES["file"]["tmp_name"])) {
        $file = $_FILES["file"]["tmp_name"];
        $tabla = $_POST["base"];
        if ($tabla == "numeros_consultar") {
            $columna = 'numero';
        } else {
            $columna = 'cuenta';
        }

        // Abrir el archivo
        $handle = fopen($file, "r");
        if ($handle) {
            // Omitir el encabezado
            fgetcsv($handle, 1000, ",");

            // Iniciar la transacción
            $conn->begin_transaction();

            try {
                $batchSize = 1000; // Tamaño del lote
                $dataBatch = [];
                $sqlBase = "INSERT IGNORE INTO $tabla ($columna) VALUES ";
                $sqlValues = [];
                $rowsInserted = 0;

                // Leer el archivo línea por línea
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $sqlValues[] = "('" . $conn->real_escape_string($data[0]) . "')";
                    if (count($sqlValues) >= $batchSize) {
                        $sql = $sqlBase . implode(", ", $sqlValues);
                        $conn->query($sql);
                        $rowsInserted += $conn->affected_rows;
                        $sqlValues = [];
                        flush();
                        ob_flush();
                    }
                }

                // Insertar las filas restantes en el último lote
                if (!empty($sqlValues)) {
                    $sql = $sqlBase . implode(", ", $sqlValues);
                    $conn->query($sql);
                    $rowsInserted += $conn->affected_rows;
                }

                // Confirmar la transacción
                $conn->commit();

                // Preparar respuesta final
                $response['status'] = 'success';
                $response['message'] = "Archivo subido e importado exitosamente: " . number_format($rowsInserted);
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                $conn->rollback();
                $response['status'] = 'error';
                $response['message'] = "Error: " . $e->getMessage();
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error al abrir el archivo.";
        }

        // Cerrar el archivo abierto
        fclose($handle);
    } else {
        $response['status'] = 'error';
        $response['message'] = "No se ha subido ningún archivo.";
    }
} else {
    $response['status'] = 'error';
    $response['message'] = "No se ha enviado una petición POST.";
}

// Cerrar la conexión
$conn->close();

// Devolver respuesta final como JSON
echo json_encode($response);
