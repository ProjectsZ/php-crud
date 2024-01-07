<?php
// Verificar la existencia del parámetro de ID antes de procesar más
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Incluir el archivo de configuración
    require_once "../config.php";

    // Preparar una declaración SELECT
    $sql = "SELECT * FROM cliente WHERE cliente_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Vincular variables a la declaración preparada como parámetros
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Establecer parámetros
        $param_id = trim($_GET["id"]);

        // Intentar ejecutar la declaración preparada
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                // Obtener la fila de resultados como un array asociativo
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Recuperar valores individuales de los campos
                $nombre = $row["Nombre"];
                $apellido = $row["Apellido"];
                $telefono = $row["Telefono"];
            } else {
                // El ID no es válido. Redirigir a la página de error
                header("location: error.php");
                exit();
            }

            // Cerrar la declaración
            mysqli_stmt_close($stmt);

            // Cerrar la conexión
            mysqli_close($link);
        } else {
            echo "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }
    }
} else {
    // El URL no contiene el parámetro de ID. Redirigir a la página de error
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ver Cliente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Ver Cliente</h1>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <p class="form-control-static"><?php echo $nombre; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Apellido</label>
                        <p class="form-control-static"><?php echo $apellido; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <p class="form-control-static"><?php echo $telefono; ?></p>
                    </div>
                    <p><a href="../index.php" class="btn btn-primary">Volver</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>