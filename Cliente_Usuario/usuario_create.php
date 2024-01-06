<?php
// Incluir archivo de configuración
require_once "config.php";

// Definir variables e inicializar con valores vacíos
$nombreUsuario = $contrasena = "";
$nombreUsuario_err = $contrasena_err = "";

// Procesar datos del formulario cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar nombre de usuario
    $input_nombreUsuario = trim($_POST["nombreUsuario"]);
    if (empty($input_nombreUsuario)) {
        $nombreUsuario_err = "Por favor ingrese el nombre de usuario.";
    } else {
        $nombreUsuario = $input_nombreUsuario;
    }

    // Validar contraseña
    $input_contrasena = trim($_POST["contrasena"]);
    if (empty($input_contrasena)) {
        $contrasena_err = "Por favor ingrese la contraseña.";
    } else {
        $contrasena = $input_contrasena;
    }

    // Verificar errores antes de insertar en la base de datos
    if (empty($nombreUsuario_err) && empty($contrasena_err)) {
        // Preparar una declaración de inserción
        $sql = "INSERT INTO Usuario (NombreUsuario, Contraseña) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Vincular variables a la declaración preparada como parámetros
            mysqli_stmt_bind_param($stmt, "ss", $param_nombreUsuario, $param_contrasena);

            // Establecer los parámetros
            $param_nombreUsuario = $nombreUsuario;
            $param_contrasena = password_hash($contrasena, PASSWORD_DEFAULT); // Hash de la contraseña

            // Intentar ejecutar la declaración preparada
            if (mysqli_stmt_execute($stmt)) {
                // Registros creados con éxito. Redirigir a la página principal
                header("location: index.php");
                exit();
            } else {
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }
        }

        // Cerrar la declaración
        mysqli_stmt_close($stmt);
    }

    // Cerrar la conexión
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
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
                        <h2>Agregar Usuario</h2>
                    </div>
                    <p>Favor diligenciar el siguiente formulario para agregar el usuario.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nombreUsuario_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre de Usuario</label>
                            <input type="text" name="nombreUsuario" class="form-control" value="<?php echo $nombreUsuario; ?>">
                            <span class="help-block"><?php echo $nombreUsuario_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($contrasena_err)) ? 'has-error' : ''; ?>">
                            <label>Contraseña</label>
                            <input type="password" name="contrasena" class="form-control" value="<?php echo $contrasena; ?>">
                            <span class="help-block"><?php echo $contrasena_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>