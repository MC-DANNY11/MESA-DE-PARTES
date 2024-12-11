<?php
// notificaciones/agregar.php
session_start();
require "../config/db_connection.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mensaje = $_POST["mensaje"];
    $id_usuario = $_POST["id_usuario"];

    $stmt = $pdo->prepare(
        "INSERT INTO notificaciones (mensaje, id_usuario) VALUES (?, ?)"
    );
    $stmt->execute([$mensaje, $id_usuario]);

    header("Location: index.php");
    exit();
}

// Obtener la lista de usuarios para seleccionar a quién enviar la notificación
$stmt = $pdo->query("SELECT id_usuario, nombre FROM usuarios");
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Notificación</title>
</head>
<body>
    <h2>Agregar Nueva Notificación</h2>
    <form method="post">
        <label>Mensaje:</label>
        <textarea name="mensaje" required></textarea>
        <br>
        <label>Enviar a:</label>
        <select name="id_usuario" required>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?php echo $usuario[
                    "id_usuario"
                ]; ?>"><?php echo $usuario["nombre"]; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit">Enviar Notificación</button>
    </form>
</body>
</html>
