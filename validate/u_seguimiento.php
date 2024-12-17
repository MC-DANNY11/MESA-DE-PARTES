<?php
session_start();

// Verificar que todos los campos requeridos están presentes
if (!empty($_POST['area']) && !empty($_POST['codigo']) && !empty($_POST['respuesta'])) {
    include "../config/db_connection.php";

    // Establecer zona horaria y obtener la fecha actual
    date_default_timezone_set('America/Lima');
    $fecha = date('Y-m-d H:i:s');

    // Capturar datos del formulario
    $area = intval($_POST['area']);
    $codigo = intval($_POST['codigo']);
    $respuesta = trim($_POST['respuesta']);
    $archivoActual = trim($_POST['archivoActual']);
    $user = 1; // Cambiar según el usuario autenticado

    // Ruta base de almacenamiento
    $carpetaDestino = '../../respuesta';

    // Verificar si se subió un nuevo archivo
    $archivoRuta = $archivoActual; // Por defecto, usar archivo actual
    if (isset($_FILES['archivonuevo']) && $_FILES['archivonuevo']['error'] === UPLOAD_ERR_OK) {
        $archivoNombre = basename($_FILES['archivonuevo']['name']);
        $archivoRutaNuevo = $carpetaDestino . '/' . $archivoNombre;

        // Crear la carpeta si no existe
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        // Mover el nuevo archivo
        if (move_uploaded_file($_FILES['archivonuevo']['tmp_name'], $archivoRutaNuevo)) {
            // Eliminar el archivo actual si existe y no es el mismo que el nuevo
            if (!empty($archivoActual) && file_exists('../../respuesta/' . $archivoActual) && $archivoActual !== $archivoNombre) {
                unlink('../../respuesta/' . $archivoActual);
            }
            $archivoRuta = $archivoNombre; // Actualizar ruta a solo el nombre del archivo
        } else {
            $_SESSION['alerta'] = 'Error al subir el nuevo archivo.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    try {
        // Consulta para obtener el nombre del área
        $query_area = "SELECT nombre FROM areas WHERE id_area = ?";
        $stmt_area = $pdo->prepare($query_area);
        $stmt_area->execute([$area]);
        $nombre_area = $stmt_area->fetchColumn();

        if (!$nombre_area) {
            throw new Exception('El área especificada no existe.');
        }

        // Actualizar la tabla `seguimiento`
        $sql_seguimiento = "UPDATE seguimiento 
                            SET id_area = ?, adjunto = ?, respuesta = ? 
                            WHERE id_seguimiento = ?";
        $stmt_seguimiento = $pdo->prepare($sql_seguimiento);
        $stmt_seguimiento->execute([$area, $archivoRuta, $respuesta, $codigo]);

        if ($stmt_seguimiento->rowCount() > 0) {
            // Insertar en el historial
            $descripcion = 'Seguimiento actualizado en el área: ' . htmlspecialchars($nombre_area);
            $sql_historial = "INSERT INTO historial_expedientes 
                              (id_expediente, id_usuario, estado_documento, descripcion, fecha_hora)
                              VALUES (?, ?, 'derivado', ?, ?)";
            $stmt_historial = $pdo->prepare($sql_historial);
            $stmt_historial->execute([$codigo, $user, $descripcion, $fecha]);

            if ($stmt_historial->rowCount() > 0) {
                $_SESSION['exito'] = 'Expediente derivado y registrado en el historial exitosamente.';
            } else {
                throw new Exception('Error al registrar los cambios en el historial.');
            }
        } else {
            $_SESSION['alerta'] = 'No se realizaron cambios en el expediente.';
        }
    } catch (PDOException $e) {
        $_SESSION['alerta'] = 'Error en la base de datos: ' . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['alerta'] = $e->getMessage();
    }

    // Redirigir al usuario
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    $_SESSION['alerta'] = 'Por favor, complete todos los campos.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
