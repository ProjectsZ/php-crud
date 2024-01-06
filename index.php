<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interfaz CRUD</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
<div class="wrapper">
        <div class="container-fluid">
            <!-- Primer contenedor: Vista previa del producto e información del producto -->
            <div class="row">
                <div class="col-md-12">
                    <div class="container-preview">
                        <h2>Vista Previa del Producto e Información</h2>
                        <!-- Agrega aquí la información y vista previa del producto -->
                    </div>
                </div>
            </div>

            <!-- Segundo contenedor: Botones de agregar, ver venta y ver usuario -->
            <div class="row">
                <div class="col-md-12">
                    <div class="container-actions">
                        <h2>Acciones</h2>
                       
                        <a href="create.php" class="btn btn-success">Agregar nuevo empleado</a>
                        <a href="Cliente/Cliente_create" class="btn btn-primary">Ver la venta</a>
                        <a href="Cliente_Usuario/Cliente_create.php" class="btn btn-info">Registrar cliente</a>
                        <a href="empleados/create.php" class="btn btn-info">Ver usuario</a>
                
                        <!-- Agrega aquí otros botones de acciones según tus necesidades -->
                    </div>
                </div>
            </div>

            <!-- Tercer contenedor: Venta y logo del usuario (nombre de usuario, cargo) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="container-user">
                        <h2>Venta y Logo del Usuario</h2>
                        <!-- Agrega aquí la información de venta y el logo del usuario -->
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Empleados</h2>
                        <a href="create.php" class="btn btn-success pull-right">Agregar nuevo empleado</a>
                    </div>
                    <?php
// Include config file
require_once "config.php";

// Primera consulta a la tabla 'cliente'
$sql_cliente = "SELECT * FROM cliente";
if($result_cliente = mysqli_query($link, $sql_cliente)){
    if(mysqli_num_rows($result_cliente) > 0){
        echo "<h2>Clientes</h2>";
        echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th>Cliente ID</th>";
                    echo "<th>Nombre</th>";
                    echo "<th>Apellido</th>";
                    echo "<th>Teléfono</th>";
                    echo "<th>Acción</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($row = mysqli_fetch_array($result_cliente)){
                echo "<tr>";
                    echo "<td>" . $row['Cliente_id'] . "</td>";
                    echo "<td>" . $row['Nombre'] . "</td>";
                    echo "<td>" . $row['Apellido'] . "</td>";
                    echo "<td>" . $row['Telefono'] . "</td>";
                    echo "<td>";
                        echo "<a href='read.php?id=". $row['Cliente_id'] ."' title='Ver' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                        echo "<a href='update.php?id=". $row['Cliente_id'] ."' title='Actualizar' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                        echo "<a href='delete.php?id=". $row['Cliente_id'] ."' title='Borrar' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                    echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";                            
        echo "</table>";
        // Liberar el conjunto de resultados
        mysqli_free_result($result_cliente);
    } else {
        echo "<p class='lead'><em>No se encontraron registros de clientes.</em></p>";
    }
} else {
    echo "ERROR: No se pudo ejecutar la consulta $sql_cliente. " . mysqli_error($link);
}

// Segunda consulta a la tabla 'employees'
$sql_empleados = "SELECT * FROM employees";
if($result_empleados = mysqli_query($link, $sql_empleados)){
    if(mysqli_num_rows($result_empleados) > 0){
        echo "<h2>Empleados</h2>";
        echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th>#</th>";
                    echo "<th>Nombre</th>";
                    echo "<th>Dirección</th>";
                    echo "<th>Sueldo</th>";
                    echo "<th>Acción</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($row = mysqli_fetch_array($result_empleados)){
                echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['address'] . "</td>";
                    echo "<td>" . $row['salary'] . "</td>";
                    echo "<td>";
                        echo "<a href='read.php?id=". $row['id'] ."' title='Ver' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                        echo "<a href='update.php?id=". $row['id'] ."' title='Actualizar' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                        echo "<a href='delete.php?id=". $row['id'] ."' title='Borrar' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                    echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";                            
        echo "</table>";
        // Liberar el conjunto de resultados
        mysqli_free_result($result_empleados);
    } else {
        echo "<p class='lead'><em>No se encontraron registros de empleados.</em></p>";
    }
} else {
    echo "ERROR: No se pudo ejecutar la consulta $sql_empleados. " . mysqli_error($link);
}

// Cerrar la conexión
mysqli_close($link);
?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>