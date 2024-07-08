<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir CSV</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <form enctype="multipart/form-data" method="post" action="upload.php">
        <select name="tabla" id="" required>
            <option value=""></option>
            <option value="numeros_consultar">numeros_consultar</option>
            <option value="cuentas_consultar">cuentas_consultar</option>
        </select>
        <input type="file" name="file" accept=".csv">
        <input type="submit" name="submit" value="Subir">
    </form>

    <script>
        $('form').on('submit', function(e) {
            e.preventDefault();
            console.log('Subiendo base...');
            var formData = new FormData(this);
            $.ajax({
                url: 'upload.php',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    var jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                        Swal.fire({
                            icon: "success",
                            title: "",
                            text: jsonResponse.message
                        });
                    } else if (jsonResponse.status === 'error'){
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response.message
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>