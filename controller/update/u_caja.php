<?php 
session_start();
if(!empty($_POST['codigo']) && !empty($_POST['nombre'])){
    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');
    
    // Recoger los datos del formulario
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'] ?? '';  // Asegurar que la descripción no sea nula
    $fecha = date("Y-m-d H:i:s");

    // Actualizar la caja
    $u_caja = "UPDATE tblcajas SET nom_caja=?, descripcion=? WHERE idCaja=?";
    $stmt_caja = $con->prepare($u_caja);
    $resultado = $stmt_caja->execute([$nombre, $descripcion, $codigo]);

    if($resultado){
        // Registrar en el historial
        $id_registro = $codigo;  // El ID de la caja actualizada
        $tblafectada = "Caja Ó Sección";
        $t_cambio = "Se actualizó datos de la caja Ó sección";
        $u_responsable = $_SESSION['id_usuario'];

        $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
        $smt_historial = $con->prepare($a_historial);
        $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha]);

        $_SESSION['exito'] = "Se ha actualizado correctamente.";
    } else {
        $_SESSION['alerta'] = "Hubo un error al actualizar: " . $stmt_caja->errorInfo()[2];
    }

    // Redirigir a la página anterior o una página de respaldo
    header('location:'.(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
} else {
    $_SESSION['alerta'] = "Todos los campos necesarios están vacíos.";
    header('location:'.(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
}
?>
