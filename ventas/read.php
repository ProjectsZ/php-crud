<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "../config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM ventas WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
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
                // URL doesn't contain valid id parameter. Redirect to error page
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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ver Venta</title>
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
                        <h1>Ver Venta</h1>
                    </div>
                    <div class="form-group">
                        <label>ID Cliente</label>
                        <p class="form-control-static"><?php echo $Cliente_id; ?></p>
                    </div>
                    <div class="form-group">
                        <label>ID Producto</label>
                        <p class="form-control-static"><?php echo $producto_id; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Cantidad Productos</label>
                        <p class="form-control-static"><?php echo $cantidad_productos; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Precio Total</label>
                        <p class="form-control-static"><?php echo $Precio_total; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Fecha Venta</label>
                        <p class="form-control-static"><?php echo $Fecha_venta; ?></p>
                    </div>
                    <div class="form-group">
                        <label>ID Usuario</label>
                        <p class="form-control-static"><?php echo $Usuario_id; ?></p>
                    </div>
                    <p><a href="../index_ventas.php" class="btn btn-primary">Volver</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
