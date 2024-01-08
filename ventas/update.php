<?php
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$Cliente_id = $producto_id = $cantidad_productos = $Precio_total = $Fecha_venta = $Usuario_id = "";
$Cliente_id_err = $producto_id_err = $cantidad_productos_err = $Precio_total_err = $Fecha_venta_err = $Usuario_id_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate Cliente_id
    $input_Cliente_id = trim($_POST["Cliente_id"]);
    if(empty($input_Cliente_id)){
        $Cliente_id_err = "Por favor ingrese el ID del cliente.";
    } else{
        $Cliente_id = $input_Cliente_id;
    }

    // Validate producto_id
    $input_producto_id = trim($_POST["producto_id"]);
    if(empty($input_producto_id)){
        $producto_id_err = "Por favor ingrese el ID del producto.";
    } else{
        $producto_id = $input_producto_id;
    }
    
    // Validate cantidad_productos
    $input_cantidad_productos = trim($_POST["cantidad_productos"]);
    if(empty($input_cantidad_productos)){
        $cantidad_productos_err = "Por favor ingrese la cantidad de productos.";     
    } else{
        $cantidad_productos = $input_cantidad_productos;
    }

    // Validate Precio_total
    $input_Precio_total = trim($_POST["Precio_total"]);
    if(empty($input_Precio_total)){
        $Precio_total_err = "Por favor ingrese el precio total.";     
    } else{
        $Precio_total = $input_Precio_total;
    }

    // Validate Fecha_venta
    $input_Fecha_venta = trim($_POST["Fecha_venta"]);
    if(empty($input_Fecha_venta)){
        $Fecha_venta_err = "Por favor ingrese la fecha de venta.";     
    } else{
        $Fecha_venta = $input_Fecha_venta;
    }

    // Validate Usuario_id
    $input_Usuario_id = trim($_POST["Usuario_id"]);
    if(empty($input_Usuario_id)){
        $Usuario_id_err = "Por favor ingrese el ID del usuario.";     
    } else{
        $Usuario_id = $input_Usuario_id;
    }
    
    // Check input errors before inserting in database
    if(empty($Cliente_id_err) && empty($producto_id_err) && empty($cantidad_productos_err) && empty($Precio_total_err) && empty($Fecha_venta_err) && empty($Usuario_id_err)){
        // Prepare an update statement
        $sql = "UPDATE ventas SET Cliente_id=?, producto_id=?, cantidad_productos=?, Precio_total=?, Fecha_venta=?, Usuario_id=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssisssi", $param_Cliente_id, $param_producto_id, $param_cantidad_productos, $param_Precio_total, $param_Fecha_venta, $param_Usuario_id, $param_id);
            
            // Set parameters
            $param_Cliente_id = $Cliente_id;
            $param_producto_id = $producto_id;
            $param_cantidad_productos = $cantidad_productos;
            $param_Precio_total = $Precio_total;
            $param_Fecha_venta = $Fecha_venta;
            $param_Usuario_id = $Usuario_id;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: ../index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM ventas WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field values
                    $Cliente_id = $row["Cliente_id"];
                    $producto_id = $row["producto_id"];
                    $cantidad_productos = $row["cantidad_productos"];
                    $Precio_total = $row["Precio_total"];
                    $Fecha_venta = $row["Fecha_venta"];
                    $Usuario_id = $row["Usuario_id"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Registro</title>
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
                        <h2>Actualizar Registro</h2>
                    </div>
                    <p>Edite los valores de entrada y envíe para actualizar el registro.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($Cliente_id_err)) ? 'has-error' : ''; ?>">
                            <label>ID Cliente</label>
                            <input type="text" name="Cliente_id" class="form-control" value="<?php echo $Cliente_id; ?>">
                            <span class="help-block"><?php echo $Cliente_id_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($producto_id_err)) ? 'has-error' : ''; ?>">
                            <label>ID Producto</label>
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
                            <label>Fecha de Venta</label>
                            <input type="text" name="Fecha_venta" class="form-control" value="<?php echo $Fecha_venta; ?>">
                            <span class="help-block"><?php echo $Fecha_venta_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Usuario_id_err)) ? 'has-error' : ''; ?>">
                            <label>ID Usuario</label>
                            <input type="text" name="Usuario_id" class="form-control" value="<?php echo $Usuario_id; ?>">
                            <span class="help-block"><?php echo $Usuario_id_err;?></span>
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
