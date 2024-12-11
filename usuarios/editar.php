<?php
session_start();
require "../config/db_connection.php";

// Verificar si el usuario está autenticado y tiene permisos de administrador
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Verificar si el parámetro 'id' está presente en la URL
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    echo "Error: El ID del usuario no es válido o no se ha proporcionado.";
    exit();
}

// Obtener el ID del usuario a editar
$id = $_GET["id"];

// Consultar los datos del usuario a editar, incluyendo el id_area
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

// Si el usuario no existe, mostrar un mensaje de error
if (!$usuario) {
    echo "Error: El usuario no existe.";
    exit();
}

// Consultar todas las áreas disponibles
$areasStmt = $pdo->prepare("SELECT * FROM Areas");
$areasStmt->execute();
$areas = $areasStmt->fetchAll();

// Procesar la actualización del usuario si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger datos del formulario
    $nombre = $_POST["nombre"];
    $nombre_usuario = $_POST["nombre_usuario"];
    $correo = $_POST["correo"];
    $rol = $_POST["rol"];
    $id_area = $_POST["id_area"]; // Cambié 'area' por 'id_area'

    // Validación básica para asegurarse de que los campos estén completos
    if (
        empty($nombre) ||
        empty($nombre_usuario) ||
        empty($correo) ||
        empty($rol) ||
        empty($id_area)
    ) {
        echo "Por favor, complete todos los campos obligatorios.";
        exit();
    }

    // Si se proporciona una nueva contraseña, la actualizamos
    if (!empty($_POST["contraseña"])) {
        $contraseña = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "UPDATE usuarios SET nombre = ?, nombre_usuario = ?, correo = ?, contraseña = ?, rol = ?, id_area = ? WHERE id_usuario = ?"
        );
        $stmt->execute([
            $nombre,
            $nombre_usuario,
            $correo,
            $contraseña,
            $rol,
            $id_area,
            $id,
        ]);
    } else {
        // Si no se proporciona una nueva contraseña, solo actualizamos los demás campos
        $stmt = $pdo->prepare(
            "UPDATE usuarios SET nombre = ?, nombre_usuario = ?, correo = ?, rol = ?, id_area = ? WHERE id_usuario = ?"
        );
        $stmt->execute([
            $nombre,
            $nombre_usuario,
            $correo,
            $rol,
            $id_area,
            $id,
        ]);
    }

    // Redirigir a la lista de usuarios después de la actualización
    header(
        "Location: index.php?popup_message=" .
            urlencode("Usuario actualizado exitosamente.")
    );
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>
    <h2>Editar Usuario</h2>
    <form method="post">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars(
            $usuario["nombre"]
        ); ?>" required>
        <br>
        <label>Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" value="<?php echo htmlspecialchars(
            $usuario["nombre_usuario"]
        ); ?>" required>
        <br>
        <label>Correo:</label>
        <input type="email" name="correo" value="<?php echo htmlspecialchars(
            $usuario["correo"]
        ); ?>" required>
        <br>
        <label>Nueva Contraseña (opcional):</label>
        <input type="password" name="contraseña">
        <br>
        <label>Rol:</label>
        <select name="rol" required>
            <option value="admin" <?php echo $usuario["rol"] == "admin"
                ? "selected"
                : ""; ?>>Admin</option>
            <option value="empleado" <?php echo $usuario["rol"] == "empleado"
                ? "selected"
                : ""; ?>>Empleado</option>
        </select>
        <br>
        <label>Área:</label>
        <select name="id_area" required>
            <?php foreach ($areas as $area): ?>
                <option value="<?php echo $area[
                    "id_area"
                ]; ?>" <?php echo $area["id_area"] == $usuario["id_area"]
    ? "selected"
    : ""; ?>>
                    <?php echo htmlspecialchars($area["nombre"]); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
