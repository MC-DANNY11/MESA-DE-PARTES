<?php 
session_start();
if(!empty($_POST['codigo']) && !empty($_POST['nombre']) && !empty($_POST['cantidad']) && !empty($_POST['editorial']) && !empty($_POST['caja'])){
    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');
    
    try {
        // Recoger los datos del formulario
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $autor = $_POST['autor'];
        $isbn = $_POST['isbn'];
        $año = $_POST['anho'];
        $cantidad = $_POST['cantidad'];
        $editorial = $_POST['editorial'];
        $caja = $_POST['caja'];
        $fecha = date("Y-m-d H:i:s");

        // Iniciar una transacción
        $con->beginTransaction();

        // Actualizar el libro
        $u_libro = "UPDATE tbllibros SET titulo=?, autor=?, isbn=?, año_publicacion=?, editorial_id=?, cantidad_total=?, idCaja=? WHERE idLibro=?";
        $stmt_libro = $con->prepare($u_libro);
        $resultado = $stmt_libro->execute([$nombre, $autor, $isbn, $año, $editorial, $cantidad, $caja, $codigo]);

        if ($resultado) {
            $id_registro = $codigo;  // Se usa el código del libro actualizado

            // Registrar en el historial
            $tblafectada = "Libros";
            $t_cambio = "Se actualizó datos del libro";
            $u_responsable = $_SESSION['id_usuario'];

            $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
            $smt_historial = $con->prepare($a_historial);
            $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha]);

            // Confirmar la transacción
            $con->commit();

            $_SESSION['exito'] = "Libro actualizado correctamente.";
        } else {
            throw new Exception("Error al actualizar los datos del libro.");
        }

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $con->rollBack();
        $_SESSION['alerta'] = "Hubo un error: " . $e->getMessage();
    }

    header('location:'.(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
} else {
    $_SESSION['alerta'] = "Todos los campos necesarios están vacíos.";
    header('location:'.(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
}
?>
