<?php 
session_start();

if (!empty($_POST['nombre']) && !empty($_POST['codigo'])) {
    include "../../config/db_connection.php";

    $codigo = $_POST['codigo'];
    $nombres = trim($_POST['nombre']); // Eliminar espacios adicionales
    $descripcion = trim($_POST['descripcion']);

    try {
        // Actualizar área
        $updateQuery = "UPDATE areas SET nombre = ?, descripcion = ? WHERE id_area = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $success = $updateStmt->execute([$nombres, $descripcion, $codigo]);

        if ($success) {
            $_SESSION['exito'] = "Área actualizada correctamente.";
        } else {
            $_SESSION['error'] = "No se pudo actualizar el área. Verifique los datos.";
        }
    } catch (PDOException $e) {
        // Manejo de errores
        $_SESSION['error'] = "Error en la base de datos: " . $e->getMessage();
    }

    // Redirigir de vuelta a la página anterior
    header('location:' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // Campos obligatorios no completados
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header('location:' . $_SERVER['HTTP_REFERER']);
    exit();
}
