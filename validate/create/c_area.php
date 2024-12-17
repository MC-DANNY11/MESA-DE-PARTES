<?php 
session_start();
if(!empty($_POST['nombre'])){
    include "../../config/db_connection.php";

    $nombres = trim($_POST['nombre']); // Eliminar espacios adicionales
    $descripcion = trim($_POST['descripcion']);

    // Verificar si el área ya existe
    $checkQuery = "SELECT COUNT(*) FROM areas WHERE nombre = ?";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->execute([$nombres]);
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        // Si el área ya existe, mostrar un mensaje de error
        $_SESSION['error'] = "El área '$nombres' ya está registrada.";
    } else {
        // Si el área no existe, proceder con la inserción
        $insertQuery = "INSERT INTO areas (nombre, descripcion) VALUES (?, ?)";
        $stmt = $pdo->prepare($insertQuery);
        $stmt->execute([$nombres, $descripcion]);

        if ($stmt) {
            $_SESSION['exito'] = "Área registrada correctamente";
        } else {
            $_SESSION['alerta'] = "Ocurrió un error al registrar el área";
        }
    }

    header('location:' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    $_SESSION['error'] = "Todos los campos son obligatorios";
    header('location:' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
