<?php
session_start();
require_once '../config/db_connection.php';
// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
// Verificar si se ha pasado el 'id' por URL
if (isset($_GET['numero'])) {
    $id_expediente = $_GET['numero'];
    // Confirmación de eliminación
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Eliminar expediente de la base de datos
        try {
            // Primero, eliminar cualquier registro relacionado en la tabla 'seguimiento' (si es necesario)
            $stmt = $pdo->prepare("DELETE FROM seguimiento WHERE id_expediente = ?");
            $stmt->execute([$id_expediente]);

            // Luego, eliminar el expediente de la tabla principal (ajustar nombre de la tabla según corresponda)
            $stmt = $pdo->prepare("DELETE FROM expedientes WHERE id_expediente = ?");
            $stmt->execute([$id_expediente]);
            // Redirigir a la lista de expedientes después de eliminar
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $error_message = "Error al eliminar el expediente: " . $e->getMessage();
        }
    }
} else {
    $error_message = "No se ha encontrado el expediente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Expediente</title>
</head>
<body>
    <h2>Confirmar Eliminación</h2>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php else: ?>
        <p>¿Está seguro de que desea eliminar este expediente?</p>
        <form method="post">
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    <?php endif; ?>
</body>
</html>
