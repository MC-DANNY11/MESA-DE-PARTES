<?php
session_start();

if (!empty($_POST['id']) && !empty($_POST['codigo']) && !empty($_POST['nombre']) && !empty($_POST['cantidad']) && !empty($_POST['editorial']) && !empty($_POST['caja']) && !empty($_POST['estado'])) {
    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');

    try {
        // Recoger los datos del formulario 
        // Datos para actualizar la tabla de libros
        $id = $_POST['id'];
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $autor = $_POST['autor'];
        $isbn = $_POST['isbn'];
        $anho = $_POST['anho'];
        $cantidad = $_POST['cantidad'];
        $editorial = $_POST['editorial'];
        $caja = $_POST['caja'];

        // Datos para actualizar en la tabla inventario
        $estado = $_POST['estado'];
        $valor = $_POST['valor'];
        $num_pago = $_POST['num_pago'];
        $observacion = $_POST['observacion'] ?? null; // Permitir que sea opcional
        $fecha = date("Y-m-d H:i:s");

        // Iniciar una transacción
        $con->beginTransaction();

        // Actualizar inventario
        $u_inventario = "UPDATE tblinventarios SET libro_id=?, estado=?, num_pago=?, valor=?, observacion=?, fecha_actualizacion=? WHERE idInventario=?";
        $stmt_inventario = $con->prepare($u_inventario);
        $inventario_actualizado = $stmt_inventario->execute([$codigo, $estado, $num_pago, $valor, $observacion, $fecha, $id]);

        if ($inventario_actualizado) {
            // Actualizar libro
            $u_libro = "UPDATE tbllibros SET titulo=?, autor=?, isbn=?, año_publicacion=?, editorial_id=?, cantidad_total=?, idCaja=? WHERE idLibro=?";
            $stmt_libro = $con->prepare($u_libro);
            $libro_actualizado = $stmt_libro->execute([$nombre, $autor, $isbn, $anho, $editorial, $cantidad, $caja, $codigo]);

            if ($libro_actualizado) {
                // Registrar en el historial
                $tblafectada = "Inventario";
                $t_cambio = "Se actualizó datos del Inventario";
                $u_responsable = $_SESSION['id_usuario'];

                $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
                $stmt_historial = $con->prepare($a_historial);
                $stmt_historial->execute([$tblafectada, $id, $t_cambio, $u_responsable, $fecha]);

                // Confirmar la transacción
                $con->commit();

                $_SESSION['exito'] = "Libro e inventario actualizados correctamente.";
            } else {
                throw new Exception("Error al actualizar los datos del libro.");
            }
        } else {
            throw new Exception("Error al actualizar los datos del inventario.");
        }

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $con->rollBack();
        $_SESSION['alerta'] = "Hubo un error: " . $e->getMessage();
    }

    // Redirigir de vuelta a la página anterior o una página de redirección
    header('location:' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
} else {
    $_SESSION['alerta'] = "Todos los campos necesarios están vacíos.";
    header('location:' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
}
?>
