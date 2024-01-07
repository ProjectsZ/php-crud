<?php
// Procesar la operación de eliminación después de la confirmación
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Incluir el archivo de configuración
    require_once "../config.php";

    // Preparar una declaración DELETE
    $sql = "DELETE FROM cliente WHERE cliente_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Vincular variables a la declaración preparada como parámetros
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Establecer parámetros
        $param_id = trim($_POST["id"]);

        // Intentar ejecutar la declaración preparada
        if (mysqli_stmt_execute($stmt)) {
            // Registros eliminados con éxito. Redirigir a la página principal
            header("location: ../index.php");
            exit();
        } else {
            echo "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }
    }

    // Cerrar la declaración
    mysqli_stmt_close($stmt);

    // Cerrar la conexión
    mysqli_close($link);
} else {
    // Verificar la existencia del parámetro de ID
    if (empty(trim($_GET["id"]))) {
        // El URL no contiene el parámetro de ID. Redirigir a la página de error
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Borrar</title>
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
                        <h1>Borrar Registro</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>" />
                            <p>¿Está seguro que desea borrar el registro?</p><br>
                            <p>
                                <input type="submit" value="Sí" class="btn btn-danger">
                                <a href="../index.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>