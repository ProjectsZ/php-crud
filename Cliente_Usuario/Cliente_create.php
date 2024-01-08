<?php
// Incluir archivo de configuración
require_once "../config.php";

// Definir variables e inicializar con valores vacíos
$nombre = $apellido = $telefono = "";
$nombre_err = $apellido_err = $telefono_err = "";

// Procesar datos del formulario cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar nombre
    $input_nombre = trim($_POST["nombre"]);
    if (empty($input_nombre)) {
        $nombre_err = "Por favor ingrese el nombre del cliente.";
    } elseif (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚüÜñÑ]+$/", $input_nombre)) {
        $nombre_err = "Por favor ingrese un nombre válido.";
    } else {
        $nombre = $input_nombre;
    }

    // Validar apellido
    $input_apellido = trim($_POST["apellido"]);
    if (empty($input_apellido)) {
        $apellido_err = "Por favor ingrese el apellido del cliente.";
    } elseif (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚüÜñÑ]+$/", $input_apellido)) {
        $apellido_err = "Por favor ingrese un apellido válido.";
    } else {
        $apellido = $input_apellido;
    }

    // Validar teléfono
    $input_telefono = trim($_POST["telefono"]);
    if (empty($input_telefono)) {
        $telefono_err = "Por favor ingrese el número de teléfono del cliente.";
    } elseif (!preg_match("/^\d{9}$/", $input_telefono)) {
        $telefono_err = "Por favor ingrese un número de teléfono válido de 9 dígitos.";
    } else {
        $telefono = $input_telefono;
    }

    // Verificar errores antes de insertar en la base de datos
    if (empty($nombre_err) && empty($apellido_err) && empty($telefono_err)) {
        // Verificar si ya existe un cliente con los mismos detalles
        $sql_verificacion = "SELECT cliente_id FROM cliente WHERE nombre = ? AND apellido = ? AND telefono = ?";
        if ($stmt_verificacion = mysqli_prepare($link, $sql_verificacion)) {
            mysqli_stmt_bind_param($stmt_verificacion, "sss", $param_nombre, $param_apellido, $param_telefono);
            
            $param_nombre = $nombre;
            $param_apellido = $apellido;
            $param_telefono = $telefono;

            mysqli_stmt_execute($stmt_verificacion);

            // Verificar si ya existe un cliente con los mismos detalles
            if (mysqli_stmt_fetch($stmt_verificacion)) {
                $telefono_err = "Ya existe un cliente con este número de teléfono.";
            } else {
                // Preparar una declaración de inserción
                $sql_insercion = "INSERT INTO cliente (nombre, apellido, telefono) VALUES (?, ?, ?)";

                if ($stmt_insercion = mysqli_prepare($link, $sql_insercion)) {
                    mysqli_stmt_bind_param($stmt_insercion, "sss", $param_nombre, $param_apellido, $param_telefono);

                    $param_nombre = $nombre;
                    $param_apellido = $apellido;
                    $param_telefono = $telefono;

                    if (mysqli_stmt_execute($stmt_insercion)) {
                        // Registros creados con éxito. Redirigir a la página principal
                        header("location: ../index_cliente.php");
                        exit();
                    } else {
                        echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
                    }

                    mysqli_stmt_close($stmt_insercion);
                }
            }

            mysqli_stmt_close($stmt_verificacion);
        }

        // Cerrar la conexión
        mysqli_close($link);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Cliente</title>
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
                        <h2>Agregar Cliente</h2>
                    </div>
                    <p>Favor diligenciar el siguiente formulario para agregar el cliente.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                            <span class="help-block">
                                <?php echo $nombre_err; ?>
                            </span>
                        </div>
                        <div class="form-group <?php echo (!empty($apellido_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="<?php echo $apellido; ?>">
                            <span class="help-block">
                                <?php echo $apellido_err; ?>
                            </span>
                        </div>
                        <div class="form-group <?php echo (!empty($telefono_err)) ? 'has-error' : ''; ?>">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="<?php echo $telefono; ?>">
                            <span class="help-block">
                                <?php echo $telefono_err; ?>
                            </span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="../index_cliente.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>