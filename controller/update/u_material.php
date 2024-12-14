<?php 
session_start();
if (!empty($_POST['codigo'])&&!empty($_POST['cantidad']) && !empty($_POST['nombre']) && !empty($_POST['estado'])){

    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');


    
    try {
        $codigo = $_POST['codigo'];
        $cantidad = $_POST['cantidad'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $cantidad = $_POST['cantidad'];
        $estado = $_POST['estado'];
        $condicion = $_POST['condicion'];
        $numpago = $_POST['num_pago'];
        $valor = $_POST['valor'];
        $observacion = $_POST['observacion'];
        $fecha_registro = date("Y-m-d H:i:s"); // Formato correcto de la fecha

        // Iniciar una transacción
        $con->beginTransaction();

        // Actualizar el libro
        $u_material = "UPDATE tblmateriales SET nombre=?, descripcion=?, cantidad=?, observacion=?, num_pago=?, estado=?, condicion=?, valor=? WHERE idMaterial=?";
        $stmt_libro = $con->prepare($u_material);
        $resultado = $stmt_libro->execute([$nombre, $descripcion,$cantidad,$observacion,$numpago,$estado, $condicion, $valor, $codigo]);

        if ($resultado) {
            $id_registro = $codigo;  // Se usa el código del libro actualizado

            // Registrar en el historial
            $tblafectada = "Materiales";
            $t_cambio = "Se actualizó datos del Material";
            $u_responsable = $_SESSION['id_usuario'];

            $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
            $smt_historial = $con->prepare($a_historial);
            $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha]);

            // Confirmar la transacción
            $con->commit();

            $_SESSION['exito'] = "Se ha actualizado correctamente.";
        } else {
            throw new Exception("Error al actualizar los datos.");
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
