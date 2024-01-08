<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "config.php";

$user_id = $_SESSION['user_id'];

$sql_user = "SELECT * FROM user";  // Cambié 'clients' a 'user'
$result_user = mysqli_query($link, $sql_user);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Interfaz CRUD</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper {
            width: 650px;
            margin: 0 auto;
        }

        .page-header h2 {
            margin-top: 0;
        }

        table tr td:last-child a {
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <!-- Agrega esto dentro de la etiqueta <head> de tu HTML -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function () {
            // Oculta el contenedor de información del usuario al cargar la página
            $(".container-user-info").hide();

            // Maneja el clic en el botón "Información de usuario"
            $("#btnInfoUsuario").click(function () {
                // Muestra u oculta el contenedor de información del usuario
                $(".container-user-info").toggle();
            });
        });
    </script>

</head>

<body>

    <script>
        $(document).ready(function () {
            $("#btnInfoUsuario").click(function () {
                $("#userInfoModal").modal("show");
            });
        });
    </script>
    <div class="wrapper">
        <div class="container-fluid">
            <!-- Formulario de inicio de sesión -->
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert" style="position: fixed; top: 10px; right: 10px;">
                        <h4 class="alert-heading">Inicio de Sesión Exitoso</h4>
                        <p>Bienvenido,
                            <?php echo $_SESSION['username']; ?>! (<a href="usuario_login/logout.php">Cerrar Sesión</a>)
                        </p>
                    </div>
                </div>
            </div>

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

                        <a href="Empleado/create.php" class="btn btn-success">Agregar nuevo empleado</a>
                        <a href="Cliente/Cliente_create" class="btn btn-primary">Ver la venta</a>
                        <a href="Cliente_Usuario/Cliente_create.php" class="btn btn-info">Registrar cliente</a>
                        <a href="#" id="btnInfoUsuario" class="btn btn-info">Información de usuario</a>

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

            <!-- Cuarto contenedor: Información del Usuario y Modificación -->
            <div class="row container-user-info">
                <div class="col-md-12">
                    <div class="container-user">
                        <h2>Información del Usuario</h2>

                        <?php
                        // Obtener y mostrar la información del usuario
                        $sql_usuario = "SELECT * FROM user WHERE user_id = ?";

                        if ($stmt_usuario = mysqli_prepare($link, $sql_usuario)) {
                            mysqli_stmt_bind_param($stmt_usuario, "i", $param_user_id);

                            $param_user_id = $user_id;

                            if (mysqli_stmt_execute($stmt_usuario)) {
                                $result_usuario = mysqli_stmt_get_result($stmt_usuario);

                                if ($row_usuario = mysqli_fetch_assoc($result_usuario)) {
                                    echo "<p><strong>Nombre de Usuario:</strong> " . htmlspecialchars($row_usuario['username']) . "</p>";
                                    echo "<p><strong>Correo Electrónico:</strong> " . htmlspecialchars($row_usuario['email']) . "</p>";

                                    // Agrega más campos según sea necesario (nombre, apellido, etc.)
                                    echo "<p><a href='editar_usuario.php' class='btn btn-primary'>Editar Información</a></p>";
                                } else {
                                    echo "<p class='lead'><em>No se encontró información del usuario.</em></p>";
                                }
                            } else {
                                echo "ERROR: No se pudo ejecutar la consulta $sql_usuario. " . mysqli_error($link);
                            }

                            mysqli_stmt_close($stmt_usuario);
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Sectores</h2>
                    
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";

                    // Primera consulta a la tabla 'cliente'
                    $sql_cliente = "SELECT * FROM cliente";
                    if ($result_cliente = mysqli_query($link, $sql_cliente)) {
                        if (mysqli_num_rows($result_cliente) > 0) {
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
                            while ($row = mysqli_fetch_array($result_cliente)) {
                                echo "<tr>";
                                echo "<td>" . $row['cliente_id'] . "</td>";
                                echo "<td>" . $row['Nombre'] . "</td>";
                                echo "<td>" . $row['Apellido'] . "</td>";
                                echo "<td>" . $row['Telefono'] . "</td>";
                                echo "<td>";
                                echo "<a href='Cliente_Usuario/read_cliente.php?id=" . $row['cliente_id'] . "' title='Ver' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                echo "<a href='Cliente_Usuario/update_cliente.php?id=" . $row['cliente_id'] . "' title='Actualizar' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                echo "<a href='Cliente_Usuario/delete_cliente.php?id=" . $row['cliente_id'] . "' title='Borrar' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
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
                    if ($result_empleados = mysqli_query($link, $sql_empleados)) {
                        if (mysqli_num_rows($result_empleados) > 0) {
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
                            while ($row = mysqli_fetch_array($result_empleados)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['address'] . "</td>";
                                echo "<td>" . $row['salary'] . "</td>";
                                echo "<td>";
                                echo "<a href='Empleado/read.php?id=" . $row['id'] . "' title='Ver' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                echo "<a href='Empleado/update.php?id=" . $row['id'] . "' title='Actualizar' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                echo "<a href='Empleado/delete.php?id=" . $row['id'] . "' title='Borrar' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
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

