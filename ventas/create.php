<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$Cliente_id = $producto_id = $cantidad_productos = $Precio_total = $Fecha_venta = $Usuario_id = "";
$Cliente_id_err = $producto_id_err = $cantidad_productos_err = $Precio_total_err = $Fecha_venta_err = $Usuario_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Cliente_id
    $input_Cliente_id = trim($_POST["Cliente_id"]);
    if (empty($input_Cliente_id)) {
        $Cliente_id_err = "Por favor ingrese el ID del Cliente.";
    } else {
        $Cliente_id = $input_Cliente_id;
    }

    // Validate producto_id
    $input_producto_id = trim($_POST["producto_id"]);
    if (empty($input_producto_id)) {
        $producto_id_err = "Por favor ingrese el ID del Producto.";
    } else {
        $producto_id = $input_producto_id;
    }

    // Validate cantidad_productos
    $input_cantidad_productos = trim($_POST["cantidad_productos"]);
    if (empty($input_cantidad_productos)) {
        $cantidad_productos_err = "Por favor ingrese la cantidad de productos.";
    } elseif (!ctype_digit($input_cantidad_productos)) {
        $cantidad_productos_err = "Por favor ingrese un valor numérico positivo.";
    } else {
        $cantidad_productos = $input_cantidad_productos;
    }

    // Validate Precio_total
    $input_Precio_total = trim($_POST["Precio_total"]);
    if (empty($input_Precio_total)) {
        $Precio_total_err = "Por favor ingrese el precio total.";
    } elseif (!is_numeric($input_Precio_total) || $input_Precio_total <= 0) {
        $Precio_total_err = "Por favor ingrese un valor numérico positivo para el precio total.";
    } else {
        $Precio_total = $input_Precio_total;
    }

    // Validate Fecha_venta
    $input_Fecha_venta = trim($_POST["Fecha_venta"]);
    if (empty($input_Fecha_venta)) {
        $Fecha_venta_err = "Por favor ingrese la fecha de la venta.";
    } else {
        $Fecha_venta = $input_Fecha_venta;
    }

    // Validate Usuario_id
    $input_Usuario_id = trim($_POST["Usuario_id"]);
    if (empty($input_Usuario_id)) {
        $Usuario_id_err = "Por favor ingrese el ID del Usuario.";
    } else {
        $Usuario_id = $input_Usuario_id;
    }

    // Check input errors before inserting in database
    if (empty($Cliente_id_err) && empty($producto_id_err) && empty($cantidad_productos_err) && empty($Precio_total_err) && empty($Fecha_venta_err) && empty($Usuario_id_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO ventas (Cliente_id, producto_id, cantidad_productos, Precio_total, Fecha_venta, Usuario_id) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssisss", $param_Cliente_id, $param_producto_id, $param_cantidad_productos, $param_Precio_total, $param_Fecha_venta, $param_Usuario_id);

            // Set parameters
            $param_Cliente_id = $Cliente_id;
            $param_producto_id = $producto_id;
            $param_cantidad_productos = $cantidad_productos;
            $param_Precio_total = $Precio_total;
            $param_Fecha_venta = $Fecha_venta;
            $param_Usuario_id = $Usuario_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: ../index_ventas.php");
                exit();
            } else {
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agregar Venta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
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
                        <h2>Agregar Venta</h2>
                    </div>
                    <p>Favor diligenciar el siguiente formulario para agregar una venta.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($Cliente_id_err)) ? 'has-error' : ''; ?>">
                            <label>ID del Cliente</label>
                            <input type="text" name="Cliente_id" class="form-control" value="<?php echo $Cliente_id; ?>">
                            <span class="help-block"><?php echo $Cliente_id_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($producto_id_err)) ? 'has-error' : ''; ?>">
                            <label>ID del Producto</label>
                            <input type="text" name="producto_id" class="form-control" value="<?php echo $producto_id; ?>">
                            <span class="help-block"><?php echo $producto_id_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($cantidad_productos_err)) ? 'has-error' : ''; ?>">
                            <label>Cantidad de Productos</label>
                            <input type="text" name="cantidad_productos" class="form-control" value="<?php echo $cantidad_productos; ?>">
                            <span class="help-block"><?php echo $cantidad_productos_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Precio_total_err)) ? 'has-error' : ''; ?>">
                            <label>Precio Total</label>
                            <input type="text" name="Precio_total" class="form-control" value="<?php echo $Precio_total; ?>">
                            <span class="help-block"><?php echo $Precio_total_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Fecha_venta_err)) ? 'has-error' : ''; ?>">
                            <label>Fecha de la Venta</label>
                            <input type="date" name="Fecha_venta" class="form-control" value="<?php echo $Fecha_venta; ?>">
                            <span class="help-block"><?php echo $Fecha_venta_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Usuario_id_err)) ? 'has-error' : ''; ?>">
                            <label>ID del Usuario</label>
                            <input type="text" name="Usuario_id" class="form-control" value="<?php echo $Usuario_id; ?>">
                            <span class="help-block"><?php echo $Usuario_id_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_ventas.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
