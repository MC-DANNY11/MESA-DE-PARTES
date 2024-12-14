<?php
session_start();
if (!empty($_POST['cod']) && !empty($_POST['hasta'])) {
    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');
    
    try {
        $cod = $_POST['cod'];
        $hasta = $_POST['hasta'];
        $fecha = date("Y-m-d H:i:s");

        // Iniciar una transacción
        $con->beginTransaction();

        // Actualizar el libro
        $u_prestamo = "UPDATE tblprestamos SET fecha_devolucion=? WHERE idPrestamo=?";
        $stmt_pres = $con->prepare($u_prestamo);
        $resultado = $stmt_pres->execute([$hasta, $cod]);

        if ($resultado) {
            $id_registro = $cod;  // $cod es el id del préstamo afectado

            // Registrar en el historial
            $tblafectada = "Prestamo";
            $t_cambio = "Se actualizó fecha de devolución del préstamo";
            $u_responsable = $_SESSION['id_usuario'];

            $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
            $smt_historial = $con->prepare($a_historial);
            $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha]);

            // Confirmar la transacción
            $con->commit();

            $_SESSION['exito'] = "Se ha actualizado correctamente.";
        } else {
            throw new Exception("Error al actualizar los datos del préstamo.");
        }

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $con->rollBack();
        $_SESSION['alerta'] = "Hubo un error: " . $e->getMessage();
    }

    header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
    exit;
} else {
    $_SESSION['alerta'] = "Todos los campos necesarios están vacíos.";
    header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
    exit;
}
?>
