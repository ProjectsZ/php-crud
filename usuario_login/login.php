<?php
session_start();

require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Utiliza sentencias preparadas para evitar inyecciones SQL
    $sql = "SELECT user_id, username, password FROM user WHERE username = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $user_id, $username, $hashed_password);
                
                if (mysqli_stmt_fetch($stmt)) {
                    // Verificar la contraseña utilizando password_verify
                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['username'] = $username;
                        header("Location: ../index.php");
                        exit();
                    } else {
                        $error_message = "Credenciales incorrectas";
                    }
                }
            } else {
                $error_message = "Credenciales incorrectas";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Error al ejecutar la consulta.";
        }
    }

    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <section class="container">
        <div class="login-container">
            <div class="circle circle-one"></div>
            <div class="form-container">
                <h1 class="opacity">LOGIN</h1>
                <!-- Aquí iría tu formulario de inicio de sesión -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="text" name="username" placeholder="Usuario" />
                    <input type="password" name="password" placeholder="Contraseña" />
                    <button type="submit">Iniciar sesión</button>
                </form>
                <div class="register-forget opacity">
                    <a href="registrar_usuario.php">REGISTRARSE</a>

                </div>
            </div>
            <div class="circle circle-two"></div>
        </div>
        <div class="theme-btn-container"></div>
    </section>

    <script>
        const themes = [
            {
                background: "#1A1A2E",
                color: "#FFFFFF",
                primaryColor: "#0F3460"
                
            },
            {
                background: "#461220",
                color: "#FFFFFF",
                primaryColor: "#E94560"
            },
            {
                background: "#192A51",
                color: "#FFFFFF",
                primaryColor: "#967AA1"
            },
            {
                background: "#F7B267",
                color: "#000000",
                primaryColor: "#F4845F"
            },
            {
                background: "#F25F5C",
                color: "#000000",
                primaryColor: "#642B36"
            },
            {
                background: "#231F20",
                color: "#FFF",
                primaryColor: "#BB4430"
            }
        ];

        const setTheme = (theme) => {
            const root = document.querySelector(":root");
            root.style.setProperty("--background", theme.background);
            root.style.setProperty("--color", theme.color);
            root.style.setProperty("--primary-color", theme.primaryColor);
        };

        const displayThemeButtons = () => {
            const btnContainer = document.querySelector(".theme-btn-container");
            themes.forEach((theme) => {
                const div = document.createElement("div");
                div.className = "theme-btn";
                div.style.cssText = `background: ${theme.background}; width: 25px; height: 25px`;
                btnContainer.appendChild(div);
                div.addEventListener("click", () => setTheme(theme));
            });
        };

        displayThemeButtons();
    </script>
</body>
</html>