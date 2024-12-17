<?php
// login.php
session_start();
require "db_connection.php";

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

        // Redirigir según el rol
        if ($user["rol"] === "admin") {
            header("Location: ../pages/index.php"); // Redirige al administrador
        } else if ($user["rol"] === "empleado") {
            header("Location: ../emp/index.php"); // Redirige al empleado
        }
        exit();
    } else {
        $error_message = "Usuario o contraseña incorrectos.";
    }
}
?>
