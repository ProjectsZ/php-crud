<?php
require_once "../config.php";

$errors = [];
$nombre = $apellido = $email = $username = $password = $confirm_password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validar la dirección de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "El formato del correo electrónico no es válido";
    } elseif (strpos($email, 'example.com') !== false) {
        $errors['email'] = "El correo electrónico no puede ser example.com";
    }

    // Verificar si las contraseñas coinciden
    if ($password != $confirm_password) {
        $errors['password'] = "Las contraseñas no coinciden";
    }

    if (empty($errors)) {
        // Si no hay errores, procede con el registro
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (nombre, apellido, email, username, password) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssss", $nombre, $apellido, $email, $username, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
                exit();
            } else {
                $errors['general'] = "Error al registrar el usuario";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_close($link);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .error-message {
            color: red;
            font-size: 12px;
            display: block;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <section class="container">
    <div class="register-container">
    <div class="circle circle-one"></div>
    <div class="form-container">
        <h1 class="opacity">REGISTRO DE USUARIO</h1>
        <!-- Formulario de Registro -->
        <form id="registrationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="text" name="email" id="emailInput" placeholder="Email" required>
            <span class="error-message" id="emailError"></span>
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" id="passwordInput" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" id="confirmPasswordInput" placeholder="Confirmar Contraseña" required>
            <span class="error-message" id="passwordError"></span>
            <button type="button" onclick="validateForm()">Registrarse</button>
        </form>

        <!-- Botón para volver a Login -->
        <a href="login.php">Volver a Login</a>
    </div>
    <div class="circle circle-two"></div>
</div>
        <div class="theme-btn-container"></div>

        <script>
            function showError(elementId, message) {
                document.getElementById(elementId).textContent = message;
            }

            function clearError(elementId) {
                document.getElementById(elementId).textContent = '';
            }

            document.getElementById('emailInput').addEventListener('input', function() {
                validateEmailFormat();
            });

            document.getElementById('passwordInput').addEventListener('input', function() {
                validatePasswordMatch();
            });

            document.getElementById('confirmPasswordInput').addEventListener('input', function() {
                validatePasswordMatch();
            });

            function validateEmailFormat() {
                var emailInput = document.getElementById('emailInput');
                var emailError = document.getElementById('emailError');

                if (!/^\S+@\S+\.\S+$/.test(emailInput.value)) {
                    showError('emailError', 'Formato de correo no válido');
                } else {
                    clearError('emailError');
                }
            }

            function validatePasswordMatch() {
                var passwordInput = document.getElementById('passwordInput');
                var confirmPasswordInput = document.getElementById('confirmPasswordInput');
                var passwordError = document.getElementById('passwordError');

                if (passwordInput.value !== confirmPasswordInput.value) {
                    showError('passwordError', 'Las contraseñas no coinciden');
                } else {
                    clearError('passwordError');
                }
            }

            function validateForm() {
                validateEmailFormat();
                validatePasswordMatch();

                // Envía el formulario si no hay errores
                if (document.getElementById('registrationForm').checkValidity()) {
                    document.getElementById('registrationForm').submit();
                }
            }
        </script>
    </section>
</body>
</html>