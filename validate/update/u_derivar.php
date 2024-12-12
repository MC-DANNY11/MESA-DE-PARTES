<?php 
session_start();

if (!empty($_POST['area']) && !empty($_POST['codigo'])) {
    include "../../config/db_connection.php";

    // Establecer zona horaria y obtener fecha y hora actual
    date_default_timezone_set('America/Lima');
    $fecha = date('Y-m-d H:i:s');

    $area = $_POST['area'];
    $codigo = $_POST['codigo'];
    $estado = 'derivado';
    $user = 1; // Cambiar este valor según el usuario autenticado

    try {
        // Obtener el nombre del área según el id_area
        $query_area = "SELECT nombre FROM areas WHERE id_area = ?";
        $stmt_area = $pdo->prepare($query_area);
        $stmt_area->execute([$area]);
        $nombre_area = $stmt_area->fetchColumn();

        if (!$nombre_area) {
            throw new Exception('No se encontró el área especificada.');
        }

        // Actualizar el estado y área del expediente
        $derivar = "UPDATE expedientes SET estado = ?, id_area = ? WHERE id_expediente = ?";
        $stmt = $pdo->prepare($derivar);
        $stmt->execute([$estado, $area, $codigo]);

        if ($stmt->rowCount() > 0) {
            // Insertar en el historial con la descripción detallada
            $descripcion = 'Expediente derivado a ' . htmlspecialchars($nombre_area);
            $historial = "INSERT INTO historial_expedientes (id_expediente, id_usuario, estado_documento, descripcion, fecha_hora)
                          VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($historial);
            $stmt->execute([$codigo, $user, $estado, $descripcion, $fecha]);

            $_SESSION['exito'] = 'Expediente derivado exitosamente';
        } else {
            $_SESSION['alerta'] = 'No se realizaron cambios en el expediente.';
        }
    } catch (PDOException $e) {
        $_SESSION['alerta'] = 'Error al derivar el expediente: ' . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['alerta'] = $e->getMessage();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    $_SESSION['alerta'] = 'Datos incompletos. Por favor, complete todos los campos.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
