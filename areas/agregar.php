<?php
// agregar.php
require "../config/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];

    $stmt = $pdo->prepare(
        "INSERT INTO areas (nombre, descripcion) VALUES (?, ?)"
    );
    $stmt->execute([$nombre, $descripcion]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Área</title>
</head>
<body>
    <h2>Agregar Nueva Área</h2>
    <form method="post">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <br>
        <label>Descripción:</label>
        <textarea name="descripcion" required></textarea>
        <br>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
