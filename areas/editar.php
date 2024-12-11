<?php
// areas/editar.php
session_start();
require "../config/db_connection.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

$id = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM areas WHERE id_area = ?");
$stmt->execute([$id]);
$area = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];

    $stmt = $pdo->prepare(
        "UPDATE areas SET nombre = ?, descripcion = ? WHERE id_area = ?"
    );
    $stmt->execute([$nombre, $descripcion, $id]);

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Área</title>
</head>
<body>
    <h2>Editar Área</h2>
    <form method="post">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $area[
            "nombre"
        ]; ?>" required>
        <br>
        <label>Descripción:</label>
        <textarea name="descripcion" required><?php echo $area[
            "descripcion"
        ]; ?></textarea>
        <br>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
