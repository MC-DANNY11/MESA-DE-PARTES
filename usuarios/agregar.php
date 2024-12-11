<?php
session_start();
require "../config/db_connection.php";

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Lógica para agregar usuario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger datos del formulario
    $nombre = $_POST["nombre"];
    $nombre_usuario = $_POST["nombre_usuario"];
    $correo = $_POST["correo"];
    $rol = $_POST["rol"];
    $area = $_POST["area"];
    $contraseña_hash = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);

    // Verificar si el correo ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $correo_existente = $stmt->fetchColumn();

    if ($correo_existente > 0) {
        // El correo ya está registrado
        header(
            "Location: index.php?popup_message=" .
                urlencode(
                    "Este correo ya está en uso. Por favor, utiliza otro correo."
                )
        );
        exit();
    } else {
        // Si el correo no existe, insertar el nuevo usuario
        $stmt = $pdo->prepare(
            "INSERT INTO usuarios (nombre, nombre_usuario, correo, contraseña, rol, id_area) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $nombre,
            $nombre_usuario,
            $correo,
            $contraseña_hash,
            $rol,
            $area,
        ]);

        // Redirigir con mensaje de éxito
        header(
            "Location: index.php?popup_message=" .
                urlencode("Usuario agregado exitosamente.")
        );
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Nuevo Usuario</title>
</head>
<body>
    <h2>Agregar Nuevo Usuario</h2>
    <form method="post">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <br>
        <label>Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required>
        <br>
        <label>Correo:</label>
        <input type="email" name="correo" required>
        <br>
        <label>Contraseña:</label>
        <input type="password" name="contraseña" required>
        <br>
        <label>Rol:</label>
        <select name="rol" required>
            <option value="admin">Admin</option>
            <option value="empleado">Empleado</option>
        </select>
        <br>
        <label>Área:</label>
        <select name="area" required>
            <?php
            // Consultar todas las áreas disponibles
            $stmt = $pdo->query("SELECT * FROM Areas");
            while ($row = $stmt->fetch()) {
                echo "<option value=\"" .
                    $row["id_area"] .
                    "\">" .
                    htmlspecialchars($row["nombre"]) .
                    "</option>";
            }
            ?>
        </select>
        <br>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
