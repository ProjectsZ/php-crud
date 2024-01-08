<?php
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$code = $name = $description = $color = $stock = $image = "";
$code_err = $name_err = $description_err = $color_err = $stock_err = $image_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate code
    $input_code = trim($_POST["code"]);
    if(empty($input_code)){
        $code_err = "Por favor ingrese un codigo valido.";     
    } else{
        $code = $input_code;
    }
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Por favor ingrese el nombre del productos.";
    // } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
    //     $name_err = "Por favor ingrese un nombre válido.";
    } else{
        $name = $input_name;
    }
    
    // Validate description
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Por favor ingrese una descripción.";     
    } else{
        $description = $input_description;
    }
    
    // Validate color
    $input_color = trim($_POST["color"]);
    if(empty($input_color)){
        $color_err = "Por favor ingrese el color del producto.";     
    }  elseif(!filter_var($input_color, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^(\#[\da-f]{3}|\#[\da-f]{6}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/")))){
        $color_err = "Por favor ingrese un color válido.";
    } else{
        $color = $input_color;
    }

    // Validate stock
    $input_stock = trim($_POST["stock"]);
    if(empty($input_stock)){
        $stock_err = "Por favor ingrese el monto del stock del producto.";     
    } elseif(!ctype_digit($input_stock)){
        $stock_err = "Por favor ingrese un valor correcto y positivo.";
    } else{
        $stock = $input_stock;
    }
    














   // Validate image
if (!empty($_FILES['image']['tmp_name'])) {
    $allowed_extensions = array("jpg", "jpeg", "png", "gif");

    // Check if the uploaded file is an image
    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    if (!in_array(strtolower($file_extension), $allowed_extensions)) {
        $image_err = "Por favor seleccione un archivo de imagen válido.";
    } else {
        // Set a unique filename to avoid overwriting existing images
        $new_image = "uploads/" . uniqid() . "." . $file_extension;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $new_image)) {
            // Remove the old image if it exists
            if (!empty($image) && file_exists($image)) {
                unlink($image);
            }

            // Set the new image path
            $image = $new_image;
        } else {
            $image_err = "Error al subir la imagen.";
        }
    }
}

// Validate other fields (code, name, description, color, stock) similarly...

// Check input errors before updating in the database
if (empty($code_err) && empty($name_err) && empty($description_err) && empty($color_err) && empty($stock_err) && empty($image_err)) {
    // Prepare an update statement
    $sql = "UPDATE productos SET code=?, name=?, description=?, color=?, stock=?, image=? WHERE id=?";

    if($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssisi", $param_code, $param_name, $param_description, $param_color, $param_stock, $param_image, $param_id);

        // Set parameters
        $param_code = $code;
        $param_name = $name;
        $param_description = $description;
        $param_color = $color;
        $param_stock = $stock;
        $param_image = $image;
        $param_id = $id;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records updated successfully. Redirect to landing page
            header("location: ../index.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}
    
    // Close connection
    mysqli_close($link);
















} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM productos WHERE id = ?";
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
                    
                    // Retrieve individual field value
                    $code = $row["code"];
                    $name = $row["name"];
                    $description = $row["description"];
                    $color = $row["color"];
                    $stock = $row["stock"];
                    $image= $row["image"];
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
    <title>Actualizar producto</title>
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
                        <h2>Actualizar producto</h2>
                    </div>
                    <p>Edite los valores de entrada y envíe para actualizar el producto.</p>
                    <!-- para que se pueda editar correctamente el form debe contener el atributo enctype='multipart/form-data' -->
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype='multipart/form-data'>
                        
                        <div class="form-group <?php echo (!empty($code_err)) ? 'has-error' : ''; ?>">
                            <label>code</label>
                            <input type="text" name="code" class="form-control" value="<?php echo $code; ?>">
                            <span class="help-block"><?php echo $code_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
                            <label>Dirección</label>
                            <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
                            <span class="help-block"><?php echo $description_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($color_err)) ? 'has-error' : ''; ?>">
                            <label>color</label>
                            <input type="text" name="color" class="form-control" value="<?php echo $color; ?>">
                            <span class="help-block"><?php echo $color_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($stock_err)) ? 'has-error' : ''; ?>">
                            <label>stock</label>
                            <input type="text" name="stock" class="form-control" value="<?php echo $stock; ?>">
                            <span class="help-block"><?php echo $stock_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($image_err)) ? 'has-error' : ''; ?>">
                            <label>imagen</label>
                            <input type='file' name='image' required>
                            <span class="help-block"><?php echo $image_err;?></span>
                        </div>
                        
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>

                    
                    <?php
                    if (!empty($image)) {
                        echo '<div class="form-group">';
                        echo '<label>Imagen Cargada</label>';
                        echo '<img src="' . $image . '" class="img-thumbnail" alt="Uploaded Image">';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>