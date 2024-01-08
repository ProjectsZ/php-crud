<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$nombre = $apellido = $telefono = "";
$nombre_err = $apellido_err = $telefono_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate nombre
    $input_nombre = trim($_POST["nombre"]);
    if (empty($input_nombre)) {
        $nombre_err = "Por favor ingrese un nombre.";
    } elseif (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚüÜñÑ]+$/", $input_nombre)) {
        $nombre_err = "Por favor ingrese un nombre válido.";
    } else {
        $nombre = $input_nombre;
    }
    
    // Validate apellido
    $input_apellido = trim($_POST["apellido"]);
    if (empty($input_apellido)) {
        $apellido_err = "Por favor ingrese un apellido.";
    } elseif (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚüÜñÑ]+$/", $input_apellido)) {
        $apellido_err = "Por favor ingrese un apellido válido.";
    } else {
        $apellido = $input_apellido;
    }
    
    // Validate telefono
    $input_telefono = trim($_POST["telefono"]);
    if (empty($input_telefono)) {
        $telefono_err = "Por favor ingrese un número de teléfono.";
    } elseif (!preg_match("/^\d{9}$/", $input_telefono)) {
        $telefono_err = "Por favor ingrese un número de teléfono válido de 9 dígitos.";
    } else {
        $telefono = $input_telefono;
    }
    
    // Check input errors before updating in the database
    if (empty($nombre_err) && empty($apellido_err) && empty($telefono_err)) {
        // Check if there is another cliente with the same details (except the current one)
        $sql_verification = "SELECT cliente_id FROM cliente WHERE nombre = ? AND apellido = ? AND telefono = ? AND cliente_id != ?";
        if ($stmt_verification = mysqli_prepare($link, $sql_verification)) {
            mysqli_stmt_bind_param($stmt_verification, "sssi", $param_nombre, $param_apellido, $param_telefono, $param_id);
            
            $param_nombre = $nombre;
            $param_apellido = $apellido;
            $param_telefono = $telefono;
            $param_id = $id;

            mysqli_stmt_execute($stmt_verification);

            // Check if there is another cliente with the same details (except the current one)
            if (mysqli_stmt_fetch($stmt_verification)) {
                $telefono_err = "Ya existe un cliente con este número de teléfono.";
            } else {
                // Prepare an update statement
                $sql_update = "UPDATE cliente SET nombre=?, apellido=?, telefono=? WHERE cliente_id=?";

                if ($stmt_update = mysqli_prepare($link, $sql_update)) {
                    mysqli_stmt_bind_param($stmt_update, "sssi", $param_nombre, $param_apellido, $param_telefono, $param_id);

                    $param_nombre = $nombre;
                    $param_apellido = $apellido;
                    $param_telefono = $telefono;
                    $param_id = $id;

                    if (mysqli_stmt_execute($stmt_update)) {
                        // Records updated successfully. Redirect to landing page
                        header("location: ../index.php");
                        exit();
                    } else {
                        echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
                    }

                    mysqli_stmt_close($stmt_update);
                }
            }

            mysqli_stmt_close($stmt_verification);
        }

        // Close connection
        mysqli_close($link);
    }
}

// Retrieve data from URL parameter
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Get URL parameter
    $id = trim($_GET["id"]);
    
    // Prepare a select statement
    $sql = "SELECT * FROM cliente WHERE cliente_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = $id;
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $nombre = $row["Nombre"];
                $apellido = $row["Apellido"];
                $telefono = $row["Telefono"];
            } else {
                // URL doesn't contain valid id. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualizar Cliente</title>
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
                        <h2>Actualizar Cliente</h2>
                    </div>
                    <p>Edite los valores de entrada y envíe para actualizar el registro.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                            <span class="help-block"><?php echo $nombre_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($apellido_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="<?php echo $apellido; ?>">
                            <span class="help-block"><?php echo $apellido_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($telefono_err)) ? 'has-error' : ''; ?>">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="<?php echo $telefono; ?>">
                            <span class="help-block"><?php echo $telefono_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="../index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>