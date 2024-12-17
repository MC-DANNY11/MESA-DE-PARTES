<?php 
session_start();

if (!empty($_POST['area']) && !empty($_POST['codigo']) && !empty($_POST['respuesta']) && isset($_FILES['archivo'])) {
    include "../config/db_connection.php";

    // Establecer zona horaria y obtener fecha y hora actual
    date_default_timezone_set('America/Lima');
    $fecha = date('Y-m-d H:i:s');

    $area = $_POST['area'];
    $codigo = $_POST['codigo'];
    $respuesta = $_POST['respuesta'];
    $estado = 'derivado';
    $user = 1; // Cambiar este valor según el usuario autenticado

    // Verificar si el archivo fue subido correctamente
    if ($_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $archivoNombre = $_FILES['archivo']['name'];
        $archivoTmp = $_FILES['archivo']['tmp_name'];
        $carpetaDestino = '../respuesta';

        // Crear la carpeta si no existe
        if (!is_dir($carpetaDestino)) {
            if (!mkdir($carpetaDestino, 0777, true)) {
                $_SESSION['alerta'] = 'Error al crear la carpeta de destino para los archivos.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
        }

        // Mover el archivo a la carpeta destino
        $archivoRuta = $carpetaDestino . '/' . basename($archivoNombre);
        if (!move_uploaded_file($archivoTmp, $archivoRuta)) {
            $_SESSION['alerta'] = 'Error al guardar el archivo en el servidor.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        $_SESSION['alerta'] = 'Error al cargar el archivo. Por favor, inténtelo nuevamente.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    try {
        // Iniciar la transacción
        $pdo->beginTransaction();

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
            // Insertar en la tabla seguimiento
            $seguimiento = "INSERT INTO seguimiento (id_expediente, fecha_hora, id_usuario, id_area, adjunto, respuesta) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_seg = $pdo->prepare($seguimiento);
            $stmt_seg->execute([$codigo, $fecha, $user, $area, $archivoRuta, $respuesta]);

            if ($stmt_seg->rowCount() <= 0) {
                throw new Exception('Error al insertar en la tabla seguimiento.');
            }

            // Insertar en la tabla historial_expedientes
            $descripcion = 'Expediente derivado a ' . htmlspecialchars($nombre_area);
            $historial = "INSERT INTO historial_expedientes (id_expediente, id_usuario, estado_documento, descripcion, fecha_hora)
                          VALUES (?, ?, ?, ?, ?)";
            $stmt_hist = $pdo->prepare($historial);
            $stmt_hist->execute([$codigo, $user, $estado, $descripcion, $fecha]);

            if ($stmt_hist->rowCount() <= 0) {
                throw new Exception('Error al insertar en la tabla historial_expedientes.');
            }

            // Confirmar la transacción
            $pdo->commit();
            $_SESSION['exito'] = 'Expediente derivado exitosamente.';
        } else {
            $pdo->rollBack();
            $_SESSION['alerta'] = 'No se realizaron cambios en el expediente.';
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['alerta'] = 'Error al derivar el expediente (PDO): ' . $e->getMessage();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['alerta'] = 'Error al derivar el expediente: ' . $e->getMessage();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    $_SESSION['alerta'] = 'Datos incompletos. Por favor, complete todos los campos.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
