<?php
// login.php
session_start();
require "config/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["nombre_usuario"];
    $password = $_POST["contraseña"];

    // Consulta para obtener el usuario y su área
    $stmt = $pdo->prepare(
        "SELECT * FROM usuarios WHERE nombre_usuario = :username"
    );
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["contraseña"])) {
        // Guardar información de usuario en la sesión, incluyendo el área
        $_SESSION["user_id"] = $user["id_usuario"];
        $_SESSION["user_role"] = $user["rol"];
        $_SESSION["user_name"] = $user["nombre"];
        $_SESSION["user_area"] = $user["id_area"]; // Agregar el área del usuario a la sesión

        header("Location: pages/index.php");
        exit();
    } else {
        $error_message = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(145deg, #e0f7fa, #c8e6e9);
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            width: 380px;
            text-align: center;
        }

        .login-container img {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            border-radius: 50%;
            background-color: #eaf7f9;
            padding: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
        }

        .login-container label {
            text-align: left;
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .login-container input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        .show-password {
            position: relative;
        }

        .show-password .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #007bff;
            font-size: 18px;
        }

        .login-container button {
            background-color: #007bff;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .login-container button:hover {
            background-color: #0056b3;
            box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.3);
        }

        .login-container .error-message {
            color: red;
            font-size: 14px;
            margin-top: 15px;
        }

        /* Responsive adjustments */
        @media (max-width: 400px) {
            .login-container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="imagenes/Seguridad.jfif" alt="Security Icon">
        <h2>Por favor, ingrese sus credenciales</h2>
        <form action="login.php" method="POST">
            <label for="nombre_usuario">Usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Ingrese su usuario" required>

            <label for="contraseña">Contraseña:</label>
            <div class="show-password">
                <input type="password" id="contraseña" name="contraseña" placeholder="Ingrese su contraseña" required>
                <span class="toggle-password" onclick="togglePassword()">👁</span>
            </div>

            <button type="submit">Ingresar</button>
        </form>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("contraseña");
            const toggleIcon = document.querySelector(".toggle-password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.textContent = "🙈";
            } else {
                passwordInput.type = "password";
                toggleIcon.textContent = "👁";
            }
        }
    </script>
</body>
</html>
